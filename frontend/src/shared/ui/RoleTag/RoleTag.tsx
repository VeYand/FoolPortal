import {Tag} from 'antd'
import {getViewableUserRole} from 'shared/libs'
import {USER_ROLE} from 'shared/types'

const tagColorLabels: Record<USER_ROLE, string> = {
	STUDENT: 'blue',
	TEACHER: 'green',
	ADMIN: 'red',
	OWNER: 'purple',
}

type RoleTagProps = {
	role: USER_ROLE,
}

const RoleTag = ({role}: RoleTagProps) => (
	<Tag
		color={tagColorLabels[role]}
	>
		{getViewableUserRole(role)}
	</Tag>
)

export {
	RoleTag,
}