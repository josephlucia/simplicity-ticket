<template>
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <div class="panel-split">
                        <div class="panel-component-heading">
                            Your Ticket Queue
                        </div>
                        <button class="btn btn-primary" @click="showCreateTicketForm">
                            Create New Ticket
                        </button>
                    </div>
                </div>
                <div class="panel-body">
                    <div class="row search-row">
                        <div class="col-md-4">
                            <div class="input-group">
                                <span class="input-group-addon">
                                    <i class="fa fa-search" aria-hidden="true"></i>
                                </span>
                                <input type="text" class="form-control" v-model="query" v-on:keyup="search"  placeholder="Search...">
                                <span class="input-group-btn">
                                    <button type="button" class="btn btn-default" @click="clearSearch">
                                        Clear
                                    </button>
                                </span>
                            </div>
                        </div>
                        <div class="col-md-2 col-md-offset-6">
                            <select class="form-control" v-model="status" @change="updateStatus">
                                <option value="open">Open Tickets</option>
                                <option value="closed">Closed Tickets</option>
                            </select>
                        </div>
                    </div>

                    <p v-if="tickets.length === 0" style="margin-top: 25px;">
                        There are currently no tickets to display.
                    </p>

                    <div class="table-responsive">
                        <table class="table table-striped table-bottom" v-if="tickets.length > 0">
                            <thead>
                                <tr>
                                    <th width="8%" class="sort" @click="sortBy('number')">
                                        Ticket #
                                        <i v-if="sortKey === 'number'" class="arrow" :class="sortOrder === 'asc' ? 'asc' : 'desc'"></i>
                                    </th>
                                    <th width="8%">Status</th>
                                    <th width="8%" class="sort" @click="sortBy('priority')">
                                        Priority
                                        <i v-if="sortKey === 'priority'" class="arrow" :class="sortOrder === 'asc' ? 'asc' : 'desc'"></i>
                                    </th>
                                    <th width="12%" class="sort" @click="sortBy('creator')">
                                        Creator
                                        <i v-if="sortKey === 'creator'" class="arrow" :class="sortOrder === 'asc' ? 'asc' : 'desc'"></i>
                                    </th>
                                    <th width="15%" class="sort" @click="sortBy('department.name')">
                                        {{ department_alias }}
                                        <i v-if="sortKey === 'department.name'" class="arrow" :class="sortOrder === 'asc' ? 'asc' : 'desc'"></i>
                                    </th>
                                    <th class="sort" @click="sortBy('subject')">
                                        Subject
                                        <i v-if="sortKey === 'subject'" class="arrow" :class="sortOrder === 'asc' ? 'asc' : 'desc'"></i>
                                    </th>
                                    <th width="10%" class="sort" @click="sortBy('created_at')">
                                        Created
                                        <i v-if="sortKey === 'created_at'" class="arrow" :class="sortOrder === 'asc' ? 'asc' : 'desc'"></i>
                                    </th>
                                    <th width="10%"></th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="ticket in tickets">
                                    <td style="vertical-align: middle;">{{ ticket.number }}</td>
                                    <td style="vertical-align: middle;" v-html="ticket.formatted_status"></td>
                                    <td style="vertical-align: middle;">{{ ticket.priority }}</td>
                                    <td style="vertical-align: middle;">{{ ticket.creator }}</td>
                                    <td style="vertical-align: middle;">{{ ticket.department.name }}</td>
                                    <td style="vertical-align: middle;">{{ ticket.subject }}</td>
                                    <td style="vertical-align: middle;">{{ ticket.formatted_date}}</td>
                                    <td style="text-align: right;" v-html="ticket.details_button"></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
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

                <!-- Create Ticket Modal -->
                <div class="modal fade" id="modal-create-ticket" tabindex="-1" role="dialog">
                    <div class="modal-dialog" v-if="createForm">
                        <div class="modal-content">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                                <h4>Create New Support Ticket</h4>
                            </div>
                            <div class="modal-body">
                                <div class="alert alert-danger" v-if="createForm.errors.length > 0">
                                    <ul>
                                        <li v-for="error in createForm.errors">{{ error }}</li>
                                    </ul>
                                </div>
                                <form class="form-horizontal" role="form">
                                    <div class="form-group">
                                        <div class="col-md-12">
                                            <label>Registered User:</label>
                                            <select class="form-control" name="customer" v-model="createForm.customer">
                                                <option value="">Please Select</option>
                                                <option :value="user.id" v-for="user in users">{{ user.name }}</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <div class="col-md-6">
                                            <label>{{ department_alias }}:</label>
                                            <select class="form-control" name="department" v-model="createForm.department">
                                                <option value="">Please Select</option>
                                                <option :value="department.id" v-for="department in departments">{{ department.name }}</option>
                                            </select>
                                        </div>
                                        <div class="col-md-6">
                                            <label>Priority:</label>
                                            <select class="form-control" name="priority" v-model="createForm.priority">
                                                <option value="High">High</option>
                                                <option value="Normal">Normal</option>
                                                <option value="Low">Low</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <div class="col-md-6">
                                            <label>Area/Room Number:</label>
                                            <input type="text" class="form-control" name="room" v-model="createForm.room">
                                        </div>
                                        <div class="col-md-6">
                                            <label>Contact Number:</label>
                                            <input type="text" class="form-control" name="phone" v-model="createForm.phone">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <div class="col-md-12">
                                            <label>Subject:</label>
                                            <input type="text" class="form-control" name="subject" v-model="createForm.subject">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <div class="col-md-12">
                                            <label>Details:</label>
                                            <textarea class="form-control" rows="5" name="details" v-model="createForm.details"></textarea>
                                        </div>
                                    </div>
                                </form>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                <button type="button" class="btn btn-primary" @click="store">
                                    <i class="fa fa-icon fa-refresh fa-spin fa-1x fa-fw" v-show="loading"></i>Create
                                </button>
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
                department_alias: Sts.department_alias,

                status: typeof sessionStorage.status === 'undefined' 
                            ? 'open' 
                            : sessionStorage.status,

                query: typeof sessionStorage.query === 'undefined' 
                            ? '' 
                            : sessionStorage.query,

                sortKey: '',
                sortOrder: 'desc',

                pagination: {
                    total: 0,
                    per_page: 10,
                    from: 1,
                    to: 0,
                    current_page: typeof sessionStorage.current_page === 'undefined' 
                                    ? 1 
                                    : sessionStorage.current_page,
                },
                offset: 4,
                rows: typeof sessionStorage.rows === 'undefined' 
                        ? 10 
                        : sessionStorage.rows,

                ticketsArray: [],
                departments: [],
                users: [],

                createForm: {
                    department: '',
                    customer: '',
                    priority: 'Normal',
                    room: '',
                    phone: '',
                    subject: '',
                    details: '',
                    errors: []
                },

                loading: false
            };
        },

        /**
         * Create the component.
         */
        created: function () {
            this.getDepartments();
        },

        /**
         * Mount the component.
         */
        mounted: function () {
            this.getTickets();
        },

        computed: {
            /**
             * The array tickets sorted if called by the user.
             */
            tickets: function() {
                var data = this.ticketsArray;
                if(this.sortKey) {
                    return _.orderBy(data, [this.sortKey], [this.sortOrder]);
                }
                return data;
            },

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
             * Get all of the departments.
             */
            getDepartments: function () {
                axios.get(Sts.url + '/tickets/departments')
                    .then(response => {
                        this.departments = response.data;
                    });
            },

            /**
             * Get all of the users.
             */
            getUsers: function () {
                axios.get(Sts.url + '/api/users')
                    .then(response => {
                        this.users = response.data;
                    });
            },

            /**
             * Get all of the tickets.
             */
            getTickets: function () {
                var page = this.pagination.current_page;

                axios.get(Sts.url + '/tickets/staff/' + this.rows + '/' + this.status + '?page=' + page + '&search=' + this.query)
                    .then(response => {
                        this.ticketsArray = response.data.data;
                        this.pagination = response.data;
                    });
            },

            /*
             * Sort by the column.
             */
            sortBy: function (key) {
                this.sortKey = key;
                this.sortOrder = this.sortOrder == 'desc' ? 'asc' : 'desc' ;
            },

            /**
             * Update the desired rows to view.
             */
            updateRows: function () {
                sessionStorage.setItem('rows', this.rows);

                this.pagination.current_page = 1;
                sessionStorage.setItem('current_page', 1);
                
                this.getTickets();
            },

            /*
             * Change the pagination page.
             */
            changePage: function (page) {
                this.pagination.current_page = page;
                sessionStorage.setItem('current_page', page);

                this.getTickets();
            },

            /**
             * Search tickets.
             */
            search: _.debounce(function () {
                sessionStorage.setItem('query', this.query);

                this.pagination.current_page = 1;
                sessionStorage.setItem('current_page', 1);
                
                this.getTickets();
            }, 500),

            /**
             * Clear the search input.
             */
            clearSearch: function () {
                this.query = '';
                sessionStorage.setItem('query', '');

                this.pagination.current_page = 1;
                sessionStorage.setItem('current_page', 1);

                this.getTickets();
            },

            /**
             * Update the ticket status for display.
             */
            updateStatus: function () {
                sessionStorage.setItem('status', this.status);

                this.pagination.current_page = 1;
                sessionStorage.setItem('current_page', 1);

                this.getTickets();
            },

            /**
             * Show the form for creating new tickets.
             */
            showCreateTicketForm: function () {
                this.getUsers();
                $('#modal-create-ticket').modal('show');
            },

            /**
             * Create a new ticket.
             */
            store: function () {
                this.loading = true;
                this.createForm.errors = [];

                axios.post(Sts.url + '/tickets', this.createForm)
                    .then(response => {
                        swal({
                            title: 'Thanks!',
                            text: 'Your ticket has been created.',
                            type: 'success',
                            showConfirmButton: false,
                            timer: 2000
                        });
                        
                        this.getTickets();

                        this.createForm.department = '';
                        this.createForm.user = '';
                        this.createForm.priority = 'Normal';
                        this.createForm.room = '';
                        this.createForm.phone = '';
                        this.createForm.subject = '';
                        this.createForm.details = '';
                        this.createForm.errors = [];
                        
                        this.loading = false;
                        $('#modal-create-ticket').modal('hide');
                    })
                    .catch(error => {
                        if(typeof error.response.data === 'object') {
                            this.loading = false;
                            this.createForm.errors = _.flatten(_.toArray(error.response.data));
                        } else {
                            this.loading = false;
                            this.createForm.errors = ['Something went wrong. Please try again.'];
                        }
                    });
            }
        }               
    }
</script>