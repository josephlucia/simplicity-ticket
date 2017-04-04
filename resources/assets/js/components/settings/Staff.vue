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
                            This management panel allows you to manage your staff and admin users.
                        </p>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <!-- List Staff -->
                            <div class="panel panel-default">
                                <div class="panel-heading">
                                    <div class="panel-split">
                                        <span class="panel-component-heading">
                                            Member Listing
                                        </span>

                                        <a class="btn btn-primary" @click="showCreateStaffForm">
                                            Add New Staff Member
                                        </a>
                                    </div>
                                </div>
                                <div class="panel-body">

                                    <p v-if="staff.length === 0">
                                        There are currently no entries.
                                    </p>

                                    <table class="table table-striped table-bottom" v-if="staff.length > 0">
                                        <thead>
                                            <tr>
                                                <th>Name</th>
                                                <th>Email</th>
                                                <th>Role</th>
                                                <th>{{ department_alias }}s</th>
                                                <th width="45"></th>
                                                <th width="45"></th>
                                                <th width="45"></th>
                                                <th width="45"></th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr v-for="member in staff" :style="member.locked ? 'text-decoration: line-through;' : ''">
                                                <td style="vertical-align: middle;">{{ member.name }}</td>
                                                <td style="vertical-align: middle;">{{ member.email }}</td>
                                                <td style="vertical-align: middle;">{{ capitalize(member.role) }}</td>
                                                <td style="vertical-align: middle;">{{ member.departments.length }}</td>
                                                <td>
                                                    <button class="btn btn-warning" @click="showEditStaffDepartmentForm(member)">
                                                        <i class="fa fa-cubes"></i>
                                                    </button>
                                                </td>
                                                <td>
                                                    <button class="btn btn-info" @click="showEditStaffForm(member)">
                                                        <i class="fa fa-pencil"></i>
                                                    </button>
                                                </td>
                                                <td>
                                                    <span v-if="member.id !== user.id">
                                                        <button class="btn btn-primary" v-if="member.locked === false" @click="lockUser(member)">
                                                            <i class="fa fa-unlock-alt"></i>
                                                        </button>
                                                        <button class="btn btn-default active" v-else @click="unlockUser(member)">
                                                            <i class="fa fa-lock"></i>
                                                        </button>
                                                    </span>
                                                </td>
                                                <td>
                                                    <span v-if="member.id !== user.id">
                                                        <button class="btn btn-danger" @click="approveStaffDelete(member)">
                                                            <i class="fa fa-times"></i>
                                                        </button>
                                                    </span>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>

                            <!-- Create Staff Modal -->
                            <div class="modal fade" id="modal-create-staff" tabindex="-1" role="dialog">
                                <div class="modal-dialog" v-if="createForm">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                                            <h4>Create New Staff Member</h4>
                                        </div>
                                        <div class="modal-body">
                                            <div class="alert alert-danger" v-if="createForm.errors.length > 0">
                                                <ul>
                                                    <li v-for="error in createForm.errors">{{ error }}</li>
                                                </ul>
                                            </div>
                                            <form class="form-horizontal" role="form">
                                                <div class="form-group">
                                                    <label class="col-md-3 control-label">Role:</label>
                                                    <div class="col-md-8">
                                                        <select class="form-control" name="role" v-model="createForm.role">
                                                            <option value="">Please Select</option>
                                                            <option value="admin">Admin</option>
                                                            <option value="staff">Staff</option>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label class="col-md-3 control-label">Name:</label>
                                                    <div class="col-md-8">
                                                        <input type="text" class="form-control" name="name" v-model="createForm.name">
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label class="col-md-3 control-label">Email Address:</label>
                                                    <div class="col-md-8">
                                                        <input type="text" class="form-control" name="email" v-model="createForm.email">
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label class="col-md-3 control-label">Temp Password:</label>
                                                    <div class="col-md-8">
                                                        <input type="text" class="form-control" name="password" v-model="createForm.password">
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

                            <!-- Edit Staff Modal -->
                            <div class="modal fade" id="modal-edit-staff" tabindex="-1" role="dialog">
                                <div class="modal-dialog" v-if="editForm">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                                            <h4>Edit Staff Member</h4>
                                        </div>
                                        <div class="modal-body">
                                            <div class="alert alert-danger" v-if="editForm.errors.length > 0">
                                                <ul>
                                                    <li v-for="error in editForm.errors">{{ error }}</li>
                                                </ul>
                                            </div>
                                            <form class="form-horizontal" role="form">
                                                <div class="form-group">
                                                    <label class="col-md-3 control-label">Role:</label>
                                                    <div class="col-md-8">
                                                        <span v-if="editForm.id === user.id">
                                                             <select class="form-control" name="role" v-model="editForm.role">
                                                                <option value="admin">Admin</option>
                                                            </select>
                                                        </span>
                                                        <span v-else>                                   
                                                            <select class="form-control" name="role" v-model="editForm.role">
                                                                <option value="admin">Admin</option>
                                                                <option value="staff">Staff</option>
                                                            </select>
                                                        </span>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label class="col-md-3 control-label">Name:</label>
                                                    <div class="col-md-8">
                                                        <input type="text" class="form-control" name="name" v-model="editForm.name">
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label class="col-md-3 control-label">Email Address:</label>
                                                    <div class="col-md-8">
                                                        <input type="text" class="form-control" name="email" v-model="editForm.email">
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

                            <!-- Delete Staff Modal -->
                            <div class="modal fade" id="modal-delete-staff" tabindex="-1" role="dialog">
                                <div class="modal-dialog" v-if="deleteStaff">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                                            <h4>Delete Staff Member ({{ deleteStaff.name }})</h4>
                                        </div>
                                        <div class="modal-body">
                                            Are you sure you want to delete this staff member? If deleted, incidents associated with 
                                            {{ deleteStaff.name }} will be orphaned. It is recommended to <a :href="transfer_url"><u>bulk transfer</u></a> 
                                            the tickets to a new staff member.
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-default" data-dismiss="modal">No, Go Back</button>
                                            <button type="button" class="btn btn-danger" @click="destroy">Yes, Delete</button>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Edit Staff Department Modal -->
                            <div class="modal fade" id="modal-edit-staff-department" tabindex="-1" role="dialog">
                                <div class="modal-dialog" v-if="editDepartmentForm">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                                            <h4>Manage {{ department_alias }}s for {{ editDepartmentForm.name }}</h4>
                                        </div>
                                        <div class="modal-body">
                                            <form class="form-horizontal" role="form">
                                                <div class="form-group">
                                                    <span class="col-md-9">
                                                        <select class="form-control" name="department" v-model="editDepartmentForm.department">
                                                            <option value="">Please Select</option>
                                                            <option :value="staffAvailableDepartment.id" v-for="staffAvailableDepartment in staffAvailableDepartments">
                                                                {{ staffAvailableDepartment.name }}
                                                            </option>
                                                        </select>
                                                    </span>
                                                    <button 
                                                        type="button" 
                                                        class="btn btn-primary" 
                                                        :class="(staffAvailableDepartments.length === 0) ? 'disabled' : ''" 
                                                        @click="storeDepartment">Add {{ department_alias }}
                                                    </button>
                                                </div>
                                            </form>
                                            <hr>
                                            <p v-if="staffDepartments.length === 0">
                                                There are currently no departments associated to this user.
                                            </p>

                                            <table class="table table-striped table-bottom" v-if="staffDepartments.length > 0">
                                                <thead>
                                                    <th>{{ department_alias }}</th>
                                                    <th width="45"></th>
                                                </thead>
                                                <tbody>
                                                    <tr v-for="department in staffDepartments">
                                                        <td style="vertical-align: middle;">{{ department.name }}</td>
                                                        <td>
                                                            <button class="btn btn-danger" @click="destroyDepartment(editDepartmentForm.id,department)">
                                                                <i class="fa fa-times"></i>
                                                            </button>
                                                        </td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
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
                department_alias: Sts.department_alias,
                transfer_url: Sts.url + '/settings/transfer',
                
                staff: [],
                staffDepartments: [],
                staffAvailableDepartments: [],

                createForm: {
                    role: '',
                    name: '',
                    email: '',
                    password: '',
                    errors: []
                },

                editForm: {
                    role: '',
                    name: '',
                    email: '',
                    errors: []
                },

                deleteStaff: {
                    name: '',
                    errors: []
                },

                editDepartmentForm: {
                    name: '',
                    department: ''
                },

                loading: false
            };
        },

        /**
         * Mount the component.
         */
        mounted: function () {
            this.getStaff();

            $('#modal-create-staff').on('shown.bs.modal', () => {
                $('#create-staff-name').focus();
            });
        },

        methods: {
            /**
             * Get all of the staff.
             */
            getStaff: function () {
                axios.get(Sts.url + '/settings/staff/all')
                    .then(response => {
                        this.staff = response.data;
                    });
            },

            /**
             * Generate a new password.
             */
            getPassword: function () {
                axios.get(Sts.url + '/settings/staff/password')
                    .then(response => {
                        this.createForm.password = response.data;
                    });
            },

            /**
             * Capitalize the first letter of the role.
             */
             capitalize: function (str) {
                return str.charAt(0).toUpperCase() + str.substr(1).toLowerCase();
             },

            /**
             * Show the form for creating new staff.
             */
            showCreateStaffForm: function () {
                this.getPassword();

                $('#modal-create-staff').modal('show');
            },

            /**
             * Create a new staff.
             */
            store: function () {
                this.loading = true;
                this.createForm.errors = [];

                axios.post(Sts.url + '/settings/staff', this.createForm)
                    .then(response => {
                        this.getStaff();

                        this.createForm.role = '';
                        this.createForm.name = '';
                        this.createForm.email = '';
                        this.createForm.password = '';
                        this.createForm.password_confirmation = '';
                        this.createForm.errors = [];
                        
                        this.loading = false;
                        $('#modal-create-staff').modal('hide');
                    })
                    .catch(error => {
                        if(typeof error.response.data === 'object') {
                            this.loading = false;
                            this.getStaff();
                            this.createForm.errors = _.flatten(_.toArray(error.response.data));
                        } else {
                            this.loading = false;
                            this.createForm.errors = ['Something went wrong. Please try again.'];
                        }
                    });
            },

            /**
             * Show the form for editing staff.
             */
            showEditStaffForm: function (member) {
                this.editForm.id = member.id;
                this.editForm.role = member.role;
                this.editForm.name = member.name;
                this.editForm.email = member.email;

                $('#modal-edit-staff').modal('show');
            },

            /**
             * Update the staff being edited.
             */
            update: function () {
                this.editForm.errors = [];

                axios.put(Sts.url + '/settings/staff/' + this.editForm.id, this.editForm)
                    .then(response => {
                        this.getStaff();

                        this.editForm.role = '';
                        this.editForm.name = '';
                        this.editForm.email = '';
                        this.editForm.errors = [];
                        
                        $('#modal-edit-staff').modal('hide');
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
             * Lock the user.
             */
            lockUser: function (member) {
                axios.post(Sts.url + '/settings/user/' + member.id + '/lock')
                    .then(response => {
                        swal({
                            title: 'Success',
                            text: 'The user has been locked.',
                            type: 'success',
                            showConfirmButton: false,
                            timer: 2000
                        });
                        
                        this.getStaff();
                    });
            },

            /**
             * Unlock the user.
             */
            unlockUser: function (member) {
                axios.post(Sts.url + '/settings/user/' + member.id + '/unlock')
                    .then(response => {
                        swal({
                            title: 'Success',
                            text: 'The user has been unlocked.',
                            type: 'success',
                            showConfirmButton: false,
                            timer: 2000
                        });

                        this.getStaff();
                    });
            },

            /**
             * Get user confirmation that the staff should be deleted.
             */
            approveStaffDelete: function (member) {
                this.deleteStaff.id = member.id;
                this.deleteStaff.name = member.name;

                $('#modal-delete-staff').modal('show');
            },

            /**
             * Delete the staff.
             */
            destroy: function () {
                this.deleteStaff.errors = [];

                axios.delete(Sts.url + '/settings/staff/' + this.deleteStaff.id)
                    .then(response => {
                        this.getStaff();

                        this.deleteStaff.errors = [];

                        $('#modal-delete-staff').modal('hide');
                    })
                    .catch(error => {
                        if(typeof error.response.data === 'object') {
                            this.deleteStaff.errors = _.flatten(_.toArray(error.response.data));
                        } else {
                            this.deleteStaff.errors = ['Something went wrong. Please try again.'];
                        }
                    });
            },

            /**
             * Get all of the staff departments.
             */
            getStaffDepartments: function (id) {
                this.getAssociatedDepartments(id);
                this.getAvailableDepartments(id);
                this.getStaff();
            },

            /**
             * Get all of the associated staff departments.
             */
            getAssociatedDepartments: function (id) {
                axios.get(Sts.url + `/settings/staff/${id}/departments/associated`)
                    .then(response => {
                        this.staffDepartments = response.data;
                    });
            },

            /**
             * Get all of the available staff departments.
             */
            getAvailableDepartments: function (id) {
                axios.get(Sts.url + `/settings/staff/${id}/departments/available`)
                    .then(response => {
                        this.staffAvailableDepartments = response.data;
                    });
            },

            /**
             * Show the form for editing staff departments.
             */
            showEditStaffDepartmentForm: function (member) {
                this.editDepartmentForm.id = member.id;
                this.editDepartmentForm.name = member.name;

                this.getStaffDepartments(member.id);

                $('#modal-edit-staff-department').modal('show');
            },

            /**
             * Add the user department.
             */
            storeDepartment: function () {
                axios.post(Sts.url + `/settings/staff/${this.editDepartmentForm.id}/departments`, this.editDepartmentForm)
                    .then(response => {
                        this.editDepartmentForm.department = '';
                        this.getStaffDepartments(this.editDepartmentForm.id);
                    });
            },

            /**
             * Delete the user department.
             */
            destroyDepartment: function (id, department) {
                axios.delete(Sts.url + `/settings/staff/${id}/departments/${department.id}`)
                    .then(response => {
                        this.getStaffDepartments(id);
                    });
            }
        }               
    }
</script>