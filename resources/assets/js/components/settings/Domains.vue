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
                            This management panel allows you to control what domains are permitted or denied from registering with
                            your system. The default action is blacklist.
                            <ul>
                                <li>Blacklist: Allow all domains except those in the table below.</li>
                                <li>Whitelist: Deny all domains except those in the table below.</li>
                            </ul>
                        </p>
                    </div>
                    <div>
                        <div class="row">
                            <div class="col-md-12">
                                <!-- List Domains -->
                                <div class="panel panel-default">
                                    <div class="panel-heading">
                                        <div class="panel-split">
                                            <div class="btn-group">
                                                <button type="button" class="btn btn-default" :class="action == 'Blacklist' ? 'active' : ''" @click="updateAction(action)">
                                                    Blacklist
                                                </button>
                                                <button type="button" class="btn btn-default" :class="action == 'Whitelist' ? 'active' : ''" @click="updateAction(action)">
                                                    Whitelist
                                                </button>
                                            </div>
                                            <button class="btn btn-primary" @click="showCreateDomainForm">
                                                Add New Domain
                                            </button>
                                        </div>
                                    </div>
                                    <div class="panel-body">

                                        <p v-if="domains.length === 0">
                                            There are currently no entries.
                                        </p>

                                        <table class="table table-striped table-bottom" v-if="domains.length > 0">
                                            <thead>
                                                <tr>
                                                    <th>Name</th>
                                                    <th width="45"></th>
                                                    <th width="45"></th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr v-for="domain in domains">
                                                    <td style="vertical-align: middle;">{{ domain.name }}</td>
                                                    <td>
                                                        <button class="btn btn-info" @click="showEditDomainForm(domain)">
                                                            <i class="fa fa-pencil"></i>
                                                        </button>
                                                    </td>
                                                    <td>
                                                        <button class="btn btn-danger" @click="approveDomainDelete(domain)">
                                                            <i class="fa fa-times"></i>
                                                        </button>
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>

                                <!-- Create Domain Modal -->
                                <div class="modal fade" id="modal-create-domain" tabindex="-1" role="dialog">
                                    <div class="modal-dialog" v-if="createForm">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                                                <h4>Add New Domain</h4>
                                            </div>
                                            <div class="modal-body">
                                                <div class="alert alert-danger" v-if="createForm.errors.length > 0">
                                                    <ul>
                                                        <li v-for="error in createForm.errors">{{ error }}</li>
                                                    </ul>
                                                </div>
                                                <form class="form-horizontal" role="form">
                                                    <div class="form-group">
                                                        <label class="col-md-3 control-label">Name:</label>
                                                        <div class="col-md-8">
                                                            <input id="create-domain-name" type="text" class="form-control" name="name" v-model="createForm.name" placeholder="example.com">
                                                        </div>
                                                    </div>
                                                </form>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                                <button type="button" class="btn btn-primary" @click="store">Create</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Edit Domain Modal -->
                                <div class="modal fade" id="modal-edit-domain" tabindex="-1" role="dialog">
                                    <div class="modal-dialog" v-if="editForm">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                                                <h4>Edit Domain</h4>
                                            </div>
                                            <div class="modal-body">
                                                <div class="alert alert-danger" v-if="editForm.errors.length > 0">
                                                    <ul>
                                                        <li v-for="error in editForm.errors">{{ error }}</li>
                                                    </ul>
                                                </div>
                                                <form class="form-horizontal" role="form">
                                                    <div class="form-group">
                                                        <label class="col-md-3 control-label">Name:</label>
                                                        <div class="col-md-8">
                                                            <input type="text" class="form-control" name="name" v-model="editForm.name">
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

                                <!-- Delete Domain Modal -->
                                <div class="modal fade" id="modal-delete-domain" tabindex="-1" role="dialog">
                                    <div class="modal-dialog" v-if="deleteDomain">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                                                <h4>Delete Domain ({{ deleteDomain.name }})</h4>
                                            </div>
                                            <div class="modal-body">
                                                <div class="alert alert-danger" v-if="deleteDomain.errors.length > 0">
                                                    <ul>
                                                        <li v-for="error in deleteDomain.errors">{{ error }}</li>
                                                    </ul>
                                                </div>

                                                Are you sure you want to delete this domain?
                                                
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
    </div>
</template>

<script>
	export default {
        /*
         * The component's data.
         */
        data: function () {
            return {
                action: '',

                domains: [],

                createForm: {
                    name: '',
                    errors: []
                },

                editForm: {
                    name: '',
                    errors: []
                },

                deleteDomain: {
                    name: '',
                    errors: []
                }
            };
        },

        /**
         * Create the component.
         */
        created: function () {
            this.getAction();
        },

        /**
         * Mount the component.
         */
        mounted: function () {
            this.getDomains();

            $('#modal-create-domain').on('shown.bs.modal', () => {
                $('#create-domain-name').focus();
            });
        },

        methods: {
            /**
             * Get the type of domain filtering.
             */
            getAction: function () {
                axios.get(Sts.url + '/settings/domains/action')
                    .then(response => {
                        this.action = response.data;
                    });
            },

            /**
             * Update the type of domain filtering.
             */
            updateAction: function (action) {
                axios.put(Sts.url + '/settings/domains/action/' + action)
                    .then(response => {
                        this.getAction();
                    });
            },

            /**
             * Get all of the domains
             */
            getDomains: function () {
                axios.get(Sts.url + '/settings/domains/all')
                    .then(response => {
                        this.domains = response.data;
                    });
            },

            /**
             * Show the form for creating new domains.
             */
            showCreateDomainForm: function () {
                $('#modal-create-domain').modal('show');
            },

            /**
             * Create a new domain.
             */
            store: function () {
                this.createForm.errors = [];

                axios.post(Sts.url + '/settings/domains', this.createForm)
                    .then(response => {
                        this.getDomains();

                        this.createForm.name = '';
                        this.createForm.errors = [];
                        
                        $('#modal-create-domain').modal('hide');
                    })
                    .catch(error => {
                        if(typeof error.response.data === 'object') {
                            this.createForm.errors = _.flatten(_.toArray(error.response.data));
                        } else {
                            this.createForm.errors = ['Something went wrong. Please try again.'];
                        }
                    });
            },

            /**
             * Show the form for editing domains.
             */
            showEditDomainForm: function (domain) {
                this.editForm.id = domain.id;
                this.editForm.name = domain.name;

                $('#modal-edit-domain').modal('show');
            },

            /**
             * Update the domain being edited.
             */
            update: function () {
                this.editForm.errors = [];

                axios.put(Sts.url + '/settings/domains/' + this.editForm.id, this.editForm)
                    .then(response => {
                        this.getDomains();

                        this.editForm.name = '';
                        this.editForm.errors = [];
                        
                        $('#modal-edit-domain').modal('hide');
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
             * Get user confirmation that the domain should be deleted.
             */
            approveDomainDelete: function (domain) {
                this.deleteDomain.id = domain.id;
                this.deleteDomain.name = domain.name;

                $('#modal-delete-domain').modal('show');
            },

            /**
             * Delete the domain.
             */
            destroy: function () {
                this.deleteDomain.errors = [];

                axios.delete(Sts.url + '/settings/domains/' + this.deleteDomain.id)
                    .then(response => {
                        this.getDomains();

                        this.deleteDomain.errors = [];

                        $('#modal-delete-domain').modal('hide');
                    })
                    .catch(error => {
                        if(typeof error.response.data === 'object') {
                            this.deleteDomain.errors = _.flatten(_.toArray(error.response.data));
                        } else {
                            this.deleteDomain.errors = ['Something went wrong. Please try again.'];
                        }
                    });
            }
        }               
    }
</script>