<template>
	<ul class="nav nav-pills">
	    <li>
	    	<a :href="url.settings"><i class="fa fa-icon fa-life-ring" aria-hidden="true"></i>Organization</a>
	    </li>
	    <li>
	    	<a :href="url.departments"><i class="fa fa-icon fa-cubes" aria-hidden="true"></i>{{ department_alias }} List</a>
	    </li>
	    <li>
	    	<a :href="url.staff"><i class="fa fa-icon fa-users" aria-hidden="true"></i>Staff List</a>
	    </li>
	    <li>
	    	<a :href="url.domains"><i class="fa fa-icon fa-filter" aria-hidden="true"></i>Domain Filtering</a>
	    </li>
	    <li>
	    	<a :href="url.transfer"><i class="fa fa-icon fa-exchange" aria-hidden="true"></i>Bulk Transfer</a>
	    </li>
	    <li>
	    	<a :href="url.registered"><i class="fa fa-icon fa-users" aria-hidden="true"></i>Registered Users</a>
	    </li>
	</ul>
</template>

<script>
	export default {
        /*
         * The component's data.
         */
        data: function () {
            return {
                department_alias: Sts.department_alias,
                url: {
                	settings: Sts.url + '/settings',
                	departments: Sts.url + '/settings/departments',
                	staff: Sts.url + '/settings/staff',
                	domains: Sts.url + '/settings/domains',
                	transfer: Sts.url + '/settings/transfer',
                	registered: Sts.url + '/settings/registered'
                }
            };
        }
	}
</script>