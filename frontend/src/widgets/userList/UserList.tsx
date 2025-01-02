import {Avatar, Button, Space, Table} from 'antd'
import {useState} from 'react'
import {USER_ROLE, UserData} from 'shared/types'
import {RoleTag} from 'shared/ui/RoleTag/RoleTag'
import {useAppSelector} from '../../shared/redux'
import {Preloader} from '../preloader/Preloader'
import {DeleteConfirmationModal} from './DeleteConfirmationModal'
import {canDeleteUser, canModifyUser} from './libs/canModifyUser'
import {useInitialize} from './libs/useInitialize'
import {UserFormModal} from './UserFormModal'


const UserList = () => {
	const {
		users,
		groups,
		subjects,
		teacherSubjects,
		saveUser,
		deleteUser,
		loading,
	} = useInitialize()

	const currentUser = useAppSelector(state => state.userEntity.user)
	const [selectedUser, setSelectedUser] = useState<UserData | undefined>()
	const [isModalVisible, setModalVisible] = useState(false)
	const [isDeleteModalVisible, setDeleteModalVisible] = useState(false)

	const handleEdit = (user: UserData) => {
		setSelectedUser(user)
		setModalVisible(true)
	}

	const handleDelete = (user: UserData) => {
		setSelectedUser(user)
		setDeleteModalVisible(true)
	}

	const groupNames = (groupIds: string[]) => {
		if (!groupIds.length) {
			return '-'
		}

		return groupIds.map(id => groups.find(group => group.groupId === id)?.name || '').join(', ')
	}

	const columns = [
		{
			title: 'Фото',
			dataIndex: 'imageSrc',
			key: 'imageSrc',
			render: (src: string) => <Avatar src={src}/>,
		},
		{
			title: 'Имя',
			dataIndex: 'firstName',
			key: 'firstName',
			render: (_: string, user: UserData) =>
				`${user.firstName} ${user.lastName} ${user.patronymic || ''}`,
		},
		{
			title: 'Email',
			dataIndex: 'email',
			key: 'email',
		},
		{
			title: 'Роль',
			dataIndex: 'role',
			key: 'role',
			render: (role: USER_ROLE) => <RoleTag role={role}/>,
		},
		{
			title: 'Группы',
			dataIndex: 'groupIds',
			key: 'groupIds',
			render: (groupIds: string[]) => groupNames(groupIds),
		},
		{
			title: 'Действия',
			key: 'actions',
			render: (_: any, user: UserData) => (
				<Space>
					{
						canModifyUser(currentUser, user) && (
							<Button onClick={() => handleEdit(user)}>
								{'Редактировать'}
							</Button>
						)
					}
					{
						canDeleteUser(currentUser, user) && (
							<Button danger onClick={() => handleDelete(user)}>
								{'Удалить'}
							</Button>
						)
					}
				</Space>
			),
		},
	]

	if (loading) {
		return <Preloader/>
	}

	return (
		<>
			<Button
				type="primary"
				onClick={() => {
					setSelectedUser(undefined)
					setModalVisible(true)
				}}
			>
				{'Добавить пользователя'}
			</Button>
			<Table columns={columns} dataSource={users} rowKey="id"/>
			{isModalVisible && (
				<UserFormModal
					user={selectedUser}
					onClose={() => setModalVisible(false)}
					onSave={saveUser}
					groups={groups}
					subjects={subjects}
					teacherSubjects={teacherSubjects}
				/>
			)}
			{isDeleteModalVisible && (
				<DeleteConfirmationModal
					user={selectedUser}
					onClose={() => setDeleteModalVisible(false)}
					onConfirm={user => deleteUser(user.userId)}
				/>
			)}
		</>
	)
}

export {
	UserList,
}
