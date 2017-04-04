<template>
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <div class="panel-split">
                        <div class="panel-component-heading">
                            Ticket Comments
                        </div>
                        <button class="btn btn-primary" @click="showCreateCommentForm">
                            Add New Comment
                        </button>
                    </div>
                </div>
                <div class="panel-body">

                    <p v-if="comments.length === 0">
                        There are currently no entries.
                    </p>

                    <div class="table-responsive">
                        <table class="table table-striped table-bottom" v-if="comments.length > 0">
                            <thead>
                                <tr>
                                    <th>Comment Detail</th>
                                    <th>Contributor</th>
                                    <th width="45"></th>
                                    <th width="45"></th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="comment in comments">
                                    <td style="vertical-align: middle;" height="50">
                                        <span :class="comment.user_id !== 0 ? 'strong' : ''">{{ comment.details }}</span>
                                        <small v-if="comment.hidden === true">
                                            <i>(hidden from creator)</i>
                                        </small>
                                    </td>
                                    <td style="vertical-align: middle;">
                                        {{ comment.user_id === user.id ? 'Me' : comment.contributor }}
                                        <small>({{ comment.formatted_date }})</small>
                                    </td>
                                    <td style="text-align: right;">
                                        <button class="btn btn-info" @click="showEditCommentForm(comment)" v-if="user.id === comment.user_id">
                                            <i class="fa fa-pencil"></i>
                                        </button>
                                    </td>
                                    <td style="text-align: right;">
                                        <button class="btn btn-danger" @click="destroy(comment)" v-if="user.id === comment.user_id">
                                            <i class="fa fa-remove"></i>
                                        </button>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Create Comment Modal -->
            <div class="modal fade" id="modal-create-comment" tabindex="-1" role="dialog">
                <div class="modal-dialog" v-if="createForm">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                            <h4>Add New Ticket Comment</h4>
                        </div>
                        <div class="modal-body">
                            <div class="alert alert-danger" v-if="createForm.errors.length > 0">
                                <ul>
                                    <li v-for="error in createForm.errors">{{ error }}</li>
                                </ul>
                            </div>
                            <form class="form-horizontal" role="form">
                                <input type="hidden" name="ticket" v-model="createForm.ticket">
                                <div class="form-group">
                                    <label class="col-md-2 control-label">Details:</label>
                                    <div class="col-md-9">
                                        <textarea class="form-control" rows="4" name="details" v-model="createForm.details"></textarea>
                                    </div>
                                </div>
                                <div class="form-group" v-if="user.role != 'user'">
                                    <div class="col-md-9 col-md-offset-2">
                                        <div class="checkbox">
                                            <label>
                                                <input type="checkbox" name="hidden" value="true" v-model="createForm.hidden">Hide comment from ticket creator.
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                            <button type="button" class="btn btn-primary" @click="store">
                                <i class="fa fa-icon fa-refresh fa-spin fa-1x fa-fw" v-show="loading"></i>Add
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Edit Comment Modal -->
            <div class="modal fade" id="modal-edit-comment" tabindex="-1" role="dialog">
                <div class="modal-dialog" v-if="editForm">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                            <h4>Edit Comment</h4>
                        </div>
                        <div class="modal-body">
                            <div class="alert alert-danger" v-if="editForm.errors.length > 0">
                                <ul>
                                    <li v-for="error in editForm.errors">{{ error }}</li>
                                </ul>
                            </div>
                            <form class="form-horizontal" role="form">
                                <div class="form-group">
                                    <label class="col-md-2 control-label">Details:</label>
                                    <div class="col-md-9">
                                        <textarea class="form-control" rows="4" name="details" v-model="editForm.details"></textarea>
                                    </div>
                                </div>
                                <div class="form-group" v-if="user.role != 'user'">
                                    <div class="col-md-9 col-md-offset-2">
                                        <div class="checkbox">
                                            <label>
                                                <input type="checkbox" name="hidden" value="true" v-model="editForm.hidden">Hide comment from ticket creator.
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                            <button type="button" class="btn btn-primary" @click="update">Update</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
	export default {
        props: ['ticket_id'],

        /*
         * The component's data.
         */
        data: function () {
            return {
                comments: [],
                user: Sts.user,

                createForm: {
                    ticket: this.ticket_id,
                    details: '',
                    hidden: '',
                    errors: []
                },

                editForm: {
                    details: '',
                    hidden: '',
                    errors: []
                },

                loading: false
            };
        },

        /**
         * Create the component.
         */
        created: function () {
            var self = this;

            Bus.$on('updateComments', function() {
                self.getComments();
            });
        },

        /**
         * Mount the component.
         */
        mounted: function () {
            this.getComments();
        },

        methods: {
            /**
             * Get all of the comments.
             */
            getComments: function () {
                axios.get(Sts.url + '/comments/' + this.ticket_id)
                    .then(response => {
                        this.comments = response.data;
                    });
            },

            /**
             * Show the form for creating new comments.
             */
            showCreateCommentForm: function () {

                $('#modal-create-comment').modal('show');
            },

            /**
             * Create a new comment.
             */
            store: function () {
                this.loading = true;
                this.createForm.errors = [];

                axios.post(Sts.url + '/comments', this.createForm)
                    .then(response => {
                        this.resetCreateForm();
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
            },

            /**
             * Reset the create form and get the comments.
             */
            resetCreateForm: function () {
                this.getComments();

                this.createForm.details = '';
                this.createForm.hidden = '';
                this.createForm.errors = [];
                
                this.loading = false;
                $('#modal-create-comment').modal('hide');
            },

            /**
             * Show the form for editing comments.
             */
            showEditCommentForm: function (comment) {
                this.editForm.id = comment.id;
                this.editForm.details = comment.details;
                this.editForm.hidden = comment.hidden;

                $('#modal-edit-comment').modal('show');
            },

            /**
             * Update the comment being edited.
             */
            update: function () {
                this.editForm.errors = [];

                axios.put(Sts.url + '/comments/' + this.editForm.id, this.editForm)
                    .then(response => {
                        this.getComments();

                        this.editForm.details = '';
                        this.editForm.hidden = '';
                        this.editForm.errors = [];
                        
                        $('#modal-edit-comment').modal('hide');
                    })
                    .catch(error => {
                        if(typeof error.response.data === 'object') {
                            this.editForm.errors = _.flatten(_.toArray(error.response.data));
                        } else {
                            this.editForm.errors = ['Something went wrong. Please try again.'];
                        }
                    });
            },

            /**
             * Delete the comment.
             */
            destroy: function (comment) {
                axios.delete(Sts.url + '/comments/' + comment.id)
                    .then(response => {
                        this.getComments();
                    });
            }
        }               
    }
</script>