import {Avatar, Button, Space, Table, Tag} from 'antd'
import {useState} from 'react'
import {GroupData, SubjectData, TeacherSubjectData, USER_ROLE, UserData} from 'shared/types'
import {TeacherSubject} from '../groupList/groupDetailsModal/SubjectListForGroup'
import {DeleteConfirmationModal} from './DeleteConfirmationModal'
import {UserFormModal} from './UserFormModal'


const mockGroups: GroupData[] = [
	{groupId: 'group1', name: 'Group 1'},
	{groupId: 'group2', name: 'Group 2'},
]

const mockUsers: UserData[] = [
	{
		userId: 'user1',
		firstName: 'John',
		lastName: 'Doe',
		patronymic: 'Smith',
		role: USER_ROLE.STUDENT,
		imageSrc: 'https://via.placeholder.com/40',
		email: 'john.doe@example.com',
		groupIds: ['group1'],
	},
	{
		userId: 'user2',
		firstName: 'Jane',
		lastName: 'Doe',
		role: USER_ROLE.TEACHER,
		email: 'jane.doe@example.com',
		groupIds: [],
	},
]

const mockSubjects: SubjectData[] = [
	{subjectId: 'subject1', name: 'Math'},
	{subjectId: 'subject2', name: 'Physics'},
]

const mockTeacherSubjects: TeacherSubjectData[] = [
	{teacherSubjectId: 'ts1', subjectId: 'subject1', teacherId: 'user2'},
]

// type UserListProps = {
// 	users: UserData[],
// 	groups: GroupData[],
// }

const UserList = () => {
	const users = mockUsers
	const groups = mockGroups
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

	const saveUser = (updatedUser: UserData) => {
		console.log('Сохранён пользователь:', updatedUser)
	}

	const saveTeacherSubjects = (updatedTeacherSubjects: TeacherSubject[]) => {
		console.log('Связи преподаватель-предмет:', updatedTeacherSubjects)
	}

	const deleteUser = (deletedUser: UserData) => {
		console.log('Deleted:', deletedUser)
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
					onSave={(updatedUser, updatedTeacherSubjects) => {
						saveUser(updatedUser)
						saveTeacherSubjects(updatedTeacherSubjects)
					}}
					groups={mockGroups}
					subjects={mockSubjects}
					teacherSubjects={mockTeacherSubjects}
				/>
			)}

			{isDeleteModalVisible && (
				<DeleteConfirmationModal
					user={selectedUser}
					onClose={() => setDeleteModalVisible(false)}
					onConfirm={deleteUser}
				/>
			)}
		</>
	)
}

export {
	UserList,
}
