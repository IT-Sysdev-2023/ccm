/** @format */

export const onlineUsers = {
	computed: {
		permissions() {
			return this.$page.props.auth.permissions
		},
		roles() {
			return this.$page.props.auth.roles
		}
	},
	methods: {
		can(permissions) {
			if (this.hasRole(['superuser'])) {
				return true
			}

			for (const permission of permissions) {nb 
				if (this.permissions.includes(permission)) {
					return true
				}
			}
		},
		hasRole(roles) {
			for (const role of roles) {
				if (this.roles.includes(role)) {
					return true
				}
			}
		}
	}
}
