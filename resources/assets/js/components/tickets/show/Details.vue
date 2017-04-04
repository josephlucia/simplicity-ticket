<template>
    <div class="row">
        <div class="col-md-12">
            <!-- Ticket Details -->
            <div class="panel panel-default">
                <div class="panel-heading">
                    <div class="panel-split">
                        <div class="panel-component-heading">
                            Ticket Number: {{ ticket.number }}
                        </div>
                        <span v-html="ticket.formatted_status"></span>
                    </div>
                </div>
                <div class="panel-body">
                    <div class="row ticket-subject-row">
                        <div class="col-md-8">
                            <div class="ticket-subject">{{ ticket.subject }}</div>
                            <div class="ticket-creator">
                                <span>{{ ticket.formatted_date }}</span> 
                                <span style="padding: 0 5px;">&bull;</span>
                                <span>Created by {{ ticket.creator }}</span>
                                <span style="padding: 0 5px;" v-if="ticket.phone">&bull;</span>
                                <span>{{ ticket.phone }}</span>
                                <span style="padding: 0 5px;" v-if="ticket.room">&bull;</span>
                                <span v-if="ticket.room">Area/Room Number: {{ ticket.room }}</span>
                            </div>
                        </div>
                    </div>
                    <div class="well ticket-well" >
                        {{ ticket.details }}
                    </div>
                    <div class="assignee">
                        <div class="label label-default" v-if="ticket.assigned_to">Ticket assigned to {{ ticket.assignment.name }}</div>
                        <div class="label label-default" v-else>Ticket is unassigned.</div>
                    </div>
                </div>
                <div class="panel-footer" style="background-color: #fff;" v-if="user.role != 'user'">
                    <div class="panel-split">
                        <span>
                            <button class="btn btn-default" @click="approveTicketClose()" v-if="ticket.resolved == false">
                                <i class="fa fa-icon fa-arrow-circle-o-down" aria-hidden="true"></i>Close Ticket
                            </button>
                            <button class="btn btn-default" @click="approveTicketOpen()" v-if="ticket.resolved == true">
                                <i class="fa fa-icon fa-arrow-circle-o-up" aria-hidden="true"></i>Re-Open Ticket
                            </button>
                        </span>
                        <button class="btn btn-info" @click="showAssignStaffForm()" v-if="ticket.resolved == false">
                            <i class="fa fa-icon fa-user" aria-hidden="true"></i>Assign Ticket
                        </button>
                    </div>
                </div>
            </div>

            <!-- Assign Ticket Owner Modal -->
            <div class="modal fade" id="modal-assign-ticket-owner" tabindex="-1" role="dialog">
                <div class="modal-dialog" v-if="assignForm">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                            <h4>Assign Ticket to Staff Member</h4>
                        </div>
                        <div class="modal-body">
                            <form role="form">
                                <div class="form-group">
                                    <select class="form-control" name="member" v-model="assignForm.member">
                                        <option value="">Please Select</option>
                                        <option v-for="member in members" :value="member.id">{{ member.name }}</option>
                                    </select>
                                </div>
                            </form>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                            <button type="button" class="btn btn-primary" @click="assign">Assign</button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Close Ticket Modal -->
            <div class="modal fade" id="modal-close-ticket" tabindex="-1" role="dialog">
                <div class="modal-dialog" v-if="closeTicket">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                            <h4>Close Ticket ({{ closeTicket.number }})</h4>
                        </div>
                        <div class="modal-body">
                            <p>Are you sure you want to close this ticket?</p>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-default" data-dismiss="modal">No, Go Back</button>
                            <button type="button" class="btn btn-danger" @click="close">Yes, Close</button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Open Ticket Modal -->
            <div class="modal fade" id="modal-open-ticket" tabindex="-1" role="dialog">
                <div class="modal-dialog" v-if="openTicket">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                            <h4>Open Ticket ({{ openTicket.number }})</h4>
                        </div>
                        <div class="modal-body">
                            <p>Are you sure you want to re-open this ticket?</p>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-default" data-dismiss="modal">No, Go Back</button>
                            <button type="button" class="btn btn-danger" @click="open">Yes, Open</button>
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
                ticket: '',
                user: Sts.user,
                members: [],
                assignForm: {},
                closeTicket: {
                    number: ''
                },
                openTicket: {
                    number: ''
                }
            };
        },

        /*
         * Mount the component.
         */
        mounted: function () {
            this.getTicketDetails();
        },

        methods: {
            /**
             * Get the ticket details.
             */
            getTicketDetails: function () {
                axios.get(Sts.url + '/tickets/details/' + this.ticket_id)
                    .then(response => {
                        this.ticket = response.data;
                    });
            },

            /**
             * Get the staff members.
             */
            getStaffMembers: function () {
                axios.get(Sts.url + '/api/staff/members')
                    .then(response => {
                        this.members = response.data;
                    });
            },

            /**
             * Show the form for assigning staff.
             */
            showAssignStaffForm: function () {
                this.assignForm.id = this.ticket.id;
                this.assignForm.member = this.ticket.assigned_to == null ? '' : this.ticket.assigned_to;

                this.getStaffMembers();

                $('#modal-assign-ticket-owner').modal('show');
            },

            /**
             * Assign the staff to the ticket.
             */
            assign: function () {
                axios.post(Sts.url + '/tickets/staff/assign/' + this.ticket_id, this.assignForm)
                    .then(response => {
                        this.getTicketDetails();

                        Bus.$emit('updateComments');

                        $('#modal-assign-ticket-owner').modal('hide');
                    });
            },

            /**
             * Get user confirmation that the ticket should be closed.
             */
            approveTicketClose: function () {
                this.closeTicket.number = this.ticket.number;

                $('#modal-close-ticket').modal('show');
            },

            /**
             * Close the open ticket.
             */
            close: function () {
                axios.post(Sts.url + '/tickets/status/close/' + this.ticket_id)
                    .then(response => {
                        this.resetTicketDetails();
                    })
                    .catch(error => {
                        if(typeof error.response.data === 'object') {
                            this.resetTicketDetails();
                            swal({
                                title: 'Ticket Closed Successfully',
                                text: 'The email failed to send.',
                                type: 'error',
                                showConfirmButton: false,
                                timer: 3000
                            });                      
                        } else {
                            this.createForm.errors = ['Something went wrong. Please try again.'];
                        }
                    });
            },

            /**
             * Get user confirmation that the ticket should be re-opened.
             */
            approveTicketOpen: function () {
                this.openTicket.number = this.ticket.number;

                $('#modal-open-ticket').modal('show');
            },

            /**
             * Open the closed ticket.
             */
            open: function () {
                axios.post(Sts.url + '/tickets/status/open/' + this.ticket_id)
                    .then(response => {
                        this.resetTicketDetails();
                    });
            },

            /**
             * Reset the ticket details.
             */
            resetTicketDetails: function () {
                this.getTicketDetails();

                Bus.$emit('updateComments');

                $('#modal-open-ticket').modal('hide');
                $('#modal-close-ticket').modal('hide');
            }
        }
    }
</script>
