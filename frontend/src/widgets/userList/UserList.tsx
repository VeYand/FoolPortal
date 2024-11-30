import {Avatar, Button, Space, Table, Tag} from 'antd'
import {useState} from 'react'
import {UserData} from 'shared/types'
import {Preloader} from '../preloader/Preloader'
import {DeleteConfirmationModal} from './DeleteConfirmationModal'
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
			render: (src: string) => <Avatar src={src} />,
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
			render: (role: string) => <Tag color={role === 'STUDENT' ? 'blue' : 'green'}>{role}</Tag>,
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
					<Button onClick={() => handleEdit(user)}>Редактировать</Button>
					<Button danger onClick={() => handleDelete(user)}>
						Удалить
					</Button>
				</Space>
			),
		},
	]

	if (loading) {
		return <Preloader/>
	}

	return (
		<>
			<Table columns={columns} dataSource={users} rowKey="id" />

			<Button type="primary" onClick={() => setModalVisible(true)}>
				{'Добавить пользователя'}
			</Button>

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
