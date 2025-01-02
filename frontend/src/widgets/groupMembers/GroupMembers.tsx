import {Table, Avatar} from 'antd'
import {getViewableUserName} from 'shared/libs'
import {useAppSelector} from 'shared/redux'
import {Preloader} from '../preloader/Preloader'
import {useInitialize} from './libs/useInitialize'


const GroupMembers = () => {
	const {loading, users, groups} = useInitialize()
	const currentUser = useAppSelector(state => state.userEntity.user)
	const userGroups = groups.filter(group => currentUser.groupIds.includes(group.groupId))
	const groupMembers = users.filter(user =>
		user.groupIds.some(groupId => currentUser.groupIds.includes(groupId)),
	)

	const tableData = groupMembers.map(user => ({
		key: user.userId,
		name: getViewableUserName(user),
		email: user.email,
		groups: user.groupIds
			.map(groupId => userGroups.find(group => group.groupId === groupId)?.name)
			.filter(Boolean)
			.join(', '),
		avatar: user.imageSrc,
	}))

	const columns = [
		{
			title: 'Аватар',
			dataIndex: 'avatar',
			key: 'avatar',
			render: (src: string | undefined) =>
				(src ? <Avatar src={src} /> : <Avatar>{'?'}</Avatar>),
		},
		{
			title: 'Имя',
			dataIndex: 'name',
			key: 'name',
		},
		{
			title: 'Email',
			dataIndex: 'email',
			key: 'email',
		},
		{
			title: 'Группы',
			dataIndex: 'groups',
			key: 'groups',
		},
	]

	if (loading) {
		return <Preloader/>
	}

	return (
		<Table
			columns={columns}
			dataSource={tableData}
			pagination={{pageSize: 10}}
			bordered
			title={() => 'Состав группы'}
		/>
	)
}

export {
	GroupMembers,
}
