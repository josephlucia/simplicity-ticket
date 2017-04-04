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
                            This management panel allows you to separate your organization into <b>{{ department_alias }}'s</b>.
                        </p>
                    </div>
                    <div>
                        <div class="row">
                            <div class="col-md-12">
                                <!-- List Departments -->
                                <div class="panel panel-default">
                                    <div class="panel-heading">
                                        <div class="panel-split">
                                            <span class="panel-component-heading">
                                                {{ department_alias }} Listing
                                            </span>
                                            <button class="btn btn-primary" @click="showCreateDepartmentForm">
                                                Add New {{ department_alias }}
                                            </button>
                                        </div>
                                    </div>
                                    <div class="panel-body">

                                        <p v-if="departments.length === 0">
                                            There are currently no entries.
                                        </p>

                                        <table class="table table-striped table-bottom" v-if="departments.length > 0">
                                            <thead>
                                                <tr>
                                                    <th>Name</th>
                                                    <th width="45"></th>
                                                    <th width="45"></th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr v-for="department in departments">
                                                    <td style="vertical-align: middle;">{{ department.name }}</td>
                                                    <td>
                                                        <button class="btn btn-info" @click="showEditDepartmentForm(department)">
                                                            <i class="fa fa-pencil"></i>
                                                        </button>
                                                    </td>
                                                    <td>
                                                        <button class="btn btn-danger" @click="approveDepartmentDelete(department)">
                                                            <i class="fa fa-times"></i>
                                                        </button>
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>

                                <!-- Create Department Modal -->
                                <div class="modal fade" id="modal-create-department" tabindex="-1" role="dialog">
                                    <div class="modal-dialog" v-if="createForm">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                                                <h4>Add New {{ department_alias }}</h4>
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
                                                            <input id="create-department-name" type="text" class="form-control" name="name" v-model="createForm.name">
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

                                <!-- Edit Department Modal -->
                                <div class="modal fade" id="modal-edit-department" tabindex="-1" role="dialog">
                                    <div class="modal-dialog" v-if="editForm">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                                                <h4>Edit {{ department_alias }}</h4>
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

                                <!-- Delete Department Modal -->
                                <div class="modal fade" id="modal-delete-department" tabindex="-1" role="dialog">
                                    <div class="modal-dialog" v-if="deleteDepartment">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                                                <h4>Delete {{ department_alias }} ({{ deleteDepartment.name }})</h4>
                                            </div>
                                            <div class="modal-body">
                                                <div class="alert alert-danger" v-if="deleteDepartment.errors.length > 0">
                                                    <ul>
                                                        <li v-for="error in deleteDepartment.errors">{{ error }}</li>
                                                    </ul>
                                                </div>

                                                Are you sure you want to delete this from your organization? This operation will fail if there are
                                                tickets associated to this entity.
                                                
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
                department_alias: Sts.department_alias,
                
                departments: [],

                createForm: {
                    name: '',
                    errors: []
                },

                editForm: {
                    name: '',
                    errors: []
                },

                deleteDepartment: {
                    name: '',
                    errors: []
                }
            };
        },

        /**
         * Mount the component.
         */
        mounted: function () {
            this.getDepartments();

            $('#modal-create-department').on('shown.bs.modal', () => {
                $('#create-department-name').focus();
            });
        },

        methods: {
            /**
             * Get all of the departments
             */
            getDepartments: function () {
                axios.get(Sts.url + '/settings/departments/all')
                    .then(response => {
                        this.departments = response.data;
                    });
            },

            /**
             * Show the form for creating new departments.
             */
            showCreateDepartmentForm: function () {
                $('#modal-create-department').modal('show');
            },

            /**
             * Create a new department.
             */
            store: function () {
                this.createForm.errors = [];

                axios.post(Sts.url + '/settings/departments', this.createForm)
                    .then(response => {
                        this.getDepartments();

                        this.createForm.name = '';
                        this.createForm.errors = [];
                        
                        $('#modal-create-department').modal('hide');
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
             * Show the form for editing departments.
             */
            showEditDepartmentForm: function (department) {
                this.editForm.id = department.id;
                this.editForm.name = department.name;

                $('#modal-edit-department').modal('show');
            },

            /**
             * Update the department being edited.
             */
            update: function () {
                this.editForm.errors = [];

                axios.put(Sts.url + '/settings/departments/' + this.editForm.id, this.editForm)
                    .then(response => {
                        this.getDepartments();

                        this.editForm.name = '';
                        this.editForm.errors = [];
                        
                        $('#modal-edit-department').modal('hide');
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
             * Get user confirmation that the department should be deleted.
             */
            approveDepartmentDelete: function (department) {
                this.deleteDepartment.errors = [];
                
                this.deleteDepartment.id = department.id;
                this.deleteDepartment.name = department.name;

                $('#modal-delete-department').modal('show');
            },

            /**
             * Delete the department.
             */
            destroy: function () {
                this.deleteDepartment.errors = [];

                axios.delete(Sts.url + '/settings/departments/' + this.deleteDepartment.id)
                    .then(response => {
                        this.getDepartments();

                        this.deleteDepartment.errors = [];

                        $('#modal-delete-department').modal('hide');
                    })
                    .catch(error => {
                        if(typeof error.response.data === 'object') {
                            this.deleteDepartment.errors = _.flatten(_.toArray(error.response.data));
                        } else {
                            this.deleteDepartment.errors = ['Something went wrong. Please try again.'];
                        }
                    });
            }
        }               
    }
</script>