<template>
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <sts-settings-nav></sts-settings-nav>
                </div>
                <div class="panel-body">
                    <div class="panel-box">
                        <p>
                            This management panel allows you to manage your registered users.
                        </p>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <!-- List Users -->
                            <div class="panel panel-default">
                                <div class="panel-heading">
                                    <div class="panel-split">
                                        <span class="panel-component-heading">
                                            Registered User Listing
                                        </span>
                                    </div>
                                </div>
                                <div class="panel-body">

                                    <p v-if="users.length === 0">
                                        There are currently no entries.
                                    </p>

                                    <table class="table table-striped table-bottom" v-if="users.length > 0">
                                        <thead>
                                            <tr>
                                                <th>Name</th>
                                                <th>Email</th>
                                                <th width="45"></th>
                                                <th width="45"></th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr v-for="user in users" :style="user.locked ? 'text-decoration: line-through;' : ''">
                                                <td style="vertical-align: middle;">{{ user.name }}</td>
                                                <td style="vertical-align: middle;">{{ user.email }}</td>
                                                <td>
                                                    <button class="btn btn-primary" v-if="user.locked === false" @click="lockUser(user)">
                                                        <i class="fa fa-unlock-alt"></i>
                                                    </button>
                                                    <button class="btn btn-default active" v-else @click="unlockUser(user)">
                                                        <i class="fa fa-lock"></i>
                                                    </button>
                                                </td>
                                                <td>
                                                    <button class="btn btn-danger" @click="approveUserDelete(user)">
                                                        <i class="fa fa-times"></i>
                                                    </button>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                                <div class="panel-footer" style="background: #fff;">
                                    <div class="panel-split">
                                        <div>
                                            <select class="form-control" v-model="rows" @change="updateRows">
                                                <option value="5">5 Rows</option>
                                                <option value="10">10 Rows</option>
                                                <option value="25">25 Rows</option>
                                                <option value="50">50 Rows</option>
                                                <option value="100">100 Rows</option>
                                                <option value="250">250 Rows</option>
                                                <option value="500">500 Rows</option>
                                            </select>
                                        </div>
                                        <div>
                                            <ul class="pagination">
                                                <li :class="atBeginning ? 'disabled' : ''">
                                                    <a href="#" aria-label="Previous" @click.prevent="!atBeginning ? changePage(pagination.current_page - 1) : null">
                                                        <i class="fa fa-caret-left" aria-hidden="true"></i>
                                                    </a>
                                                </li>
                                                <li v-show="pagination.total > 0" :class="pagination.current_page == 1 ? 'active' : ''">
                                                    <a href="#" @click.prevent="changePage(1)">1</a>
                                                </li>
                                                <li v-for="page in pages" :class="page == isActive ? 'active' : ''">
                                                    <a href="#" @click.prevent="changePage(page)">{{ page }}</a>
                                                </li>
                                                <li v-show="pagination.last_page > 1" :class="pagination.current_page == pagination.last_page ? 'active' : ''">
                                                    <a href="#" @click.prevent="changePage(pagination.last_page)">{{ pagination.last_page }}</a>
                                                </li>
                                                <li :class="atEnd ? 'disabled' : ''">
                                                    <a href="#" aria-label="Next" @click.prevent="!atEnd ? changePage(pagination.current_page + 1) : null">
                                                        <i class="fa fa-caret-right" aria-hidden="true"></i>
                                                    </a>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Delete User Modal -->
                            <div class="modal fade" id="modal-delete-registered" tabindex="-1" role="dialog">
                                <div class="modal-dialog" v-if="deleteUser">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                                            <h4>Delete Registered User ({{ deleteUser.name }})</h4>
                                        </div>
                                        <div class="modal-body">
                                            Are you sure you want to delete this registered user? All tickets associated to this person will
                                            be deleted and therefor not recoverable.
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-default" data-dismiss="modal">No, Go Back</button>
                                            <button type="button" class="btn btn-danger" @click="destroy">Yes, Delete</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
	export default {
        /*
         * The component's data.
         */
        data: function () {
            return {
                user: Sts.user,

                pagination: {
                    total: 0,
                    per_page: 10,
                    from: 1,
                    to: 0,
                    current_page: 1
                },
                offset: 3,
                rows: 10,
                
                users: [],

                deleteUser: {
                    name: '',
                    errors: []
                }
            };
        },

        /**
         * Mount the component.
         */
        mounted: function () {
            this.getUsers();
        },

        computed: {
            /**
             * Return the page to set the active class.
             */
            isActive: function () {
                return this.pagination.current_page;
            },

            /**
             * Determine if the user is on the first pagination page.
             */
            atBeginning: function () {
                return this.pagination.current_page === 1;
            },

            /**
             * Determine if the user is on the last pagination page.
             */
            atEnd: function () {
                return this.pagination.current_page === this.pagination.last_page || this.pagination.last_page === 0;
            },

            /**
             * Display the array pages available to click.
             */
            pages: function () {
                if(!this.pagination.to) {
                    return [];
                }
                var from = this.pagination.current_page - this.offset;
                if(from < 1) {
                    from = 1;
                }
                var to = from + (this.offset * 2);
                if (to >= this.pagination.last_page) {
                    to = this.pagination.last_page - 1;
                }
                var pagesArray = [];
                for (from = from + 1; from <= to; from++) {
                    pagesArray.push(from);
                }
                return pagesArray;
            }
        },

        methods: {
            /**
             * Get all of the staff.
             */
            getUsers: function () {
                var page = this.pagination.current_page;

                axios.get(Sts.url + '/settings/registered/' + this.rows + '/?page=' + page)
                    .then(response => {
                        this.users = response.data.data;
                        this.pagination = response.data;
                        console.log(response.data);
                    });
            },

            /**
             * Update the desired rows to view.
             */
            updateRows: function () {
                this.pagination.current_page = 1;              
                this.getUsers();
            },

            /*
             * Change the pagination page.
             */
            changePage: function (page) {
                this.pagination.current_page = page;
                this.getUsers();
            },

            /**
             * Lock the user.
             */
            lockUser: function (user) {
                axios.post(Sts.url + '/settings/user/' + user.id + '/lock')
                    .then(response => {
                        swal({
                            title: 'Success',
                            text: 'The user has been locked.',
                            type: 'success',
                            showConfirmButton: false,
                            timer: 2000
                        });
                        
                        this.getUsers();
                    });
            },

            /**
             * Unlock the user.
             */
            unlockUser: function (user) {
                axios.post(Sts.url + '/settings/user/' + user.id + '/unlock')
                    .then(response => {
                        swal({
                            title: 'Success',
                            text: 'The user has been unlocked.',
                            type: 'success',
                            showConfirmButton: false,
                            timer: 2000
                        });

                        this.getUsers();
                    });
            },

            /**
             * Get user confirmation that the staff should be deleted.
             */
            approveUserDelete: function (user) {
                this.deleteUser.id = user.id;
                this.deleteUser.name = user.name;

                $('#modal-delete-registered').modal('show');
            },

            /**
             * Delete the staff.
             */
            destroy: function () {
                this.deleteUser.errors = [];

                axios.delete(Sts.url + '/settings/registered/' + this.deleteUser.id)
                    .then(response => {
                        this.getUsers();

                        this.deleteUser.errors = [];

                        $('#modal-delete-registered').modal('hide');
                    })
                    .catch(error => {
                        if(typeof error.response.data === 'object') {
                            this.deleteUser.errors = _.flatten(_.toArray(error.response.data));
                        } else {
                            this.deleteUser.errors = ['Something went wrong. Please try again.'];
                        }
                    });
            }
        }               
    }
</script>