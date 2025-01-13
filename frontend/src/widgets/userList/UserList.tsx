import {SearchOutlined} from '@ant-design/icons'
import {Avatar, Button, Input, Space, Table} from 'antd'
import {useState} from 'react'
import {getViewableUserRole} from 'shared/libs'
import {useAppSelector} from 'shared/redux'
import {USER_ROLE, UserData} from 'shared/types'
import {RoleTag} from 'shared/ui/RoleTag/RoleTag'
import {Preloader} from '../preloader/Preloader'
import {DeleteConfirmationModal} from './DeleteConfirmationModal'
import {canDeleteUser, canModifyUser} from './libs/canModifyUser'
import {useInitialize} from './libs/useInitialize'
import {UserFormModal} from './UserFormModal'


const UserList = () => {
	const [pagination, setPagination] = useState({
		current: 1,
		pageSize: 10,
	})
	const {
		users,
		groups,
		subjects,
		teacherSubjects,
		saveUser,
		deleteUser,
		loading,
		refetch,
	} = useInitialize(undefined, undefined, pagination.current, pagination.pageSize, undefined)

	const currentUser = useAppSelector(state => state.userEntity.user)
	const [selectedUser, setSelectedUser] = useState<UserData | undefined>()
	const [isModalVisible, setModalVisible] = useState(false)
	const [isDeleteModalVisible, setDeleteModalVisible] = useState(false)
	const [searchText, setSearchText] = useState('')

	const handleTableChange = (paginationData: any, filters: any, sorter: any) => {
		const sortOrder = sorter.order ? sorter.order === 'ascend' ? 'ASC' : 'DESC' : undefined
		const sortField = sorter.field

		setPagination({
			current: paginationData.current,
			pageSize: paginationData.pageSize,
		})

		refetch(sortOrder, sortField, paginationData.current, paginationData.pageSize, filters.role)
	}


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

		return groupIds
			.map(id => groups.find(group => group.groupId === id)?.name || '')
			.join(', ')
	}

	const filteredData = users.filter(user => {
		const searchString = `${user.firstName} ${user.lastName} ${user.email} ${getViewableUserRole(user.role)} ${groupNames(user.groupIds)}`.toLowerCase()
		return searchString.includes(searchText.toLowerCase())
	})

	const columns = [
		{
			title: 'Фото',
			dataIndex: 'imageSrc',
			key: 'imageSrc',
			render: (src: string) => <Avatar src={src}/>,
		},
		{
			title: 'Имя',
			dataIndex: 'name',
			key: 'name',
			sorter: (a: UserData, b: UserData) =>
				`${a.firstName} ${a.lastName}`.localeCompare(`${b.firstName} ${b.lastName}`),
			render: (_: string, user: UserData) =>
				`${user.firstName} ${user.lastName} ${user.patronymic || ''}`,
		},
		{
			title: 'Email',
			dataIndex: 'email',
			key: 'email',
			sorter: (a: UserData, b: UserData) => a.email.localeCompare(b.email),
		},
		{
			title: 'Роль',
			dataIndex: 'role',
			key: 'role',
			filters: Object.values(USER_ROLE).map(role => ({
				text: getViewableUserRole(role),
				value: role,
			})),
			onFilter: (value: any, record: UserData) => record.role === value,
			render: (role: USER_ROLE) => <RoleTag role={role} />,
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
					{canModifyUser(currentUser, user) && (
						<Button onClick={() => handleEdit(user)}>Редактировать</Button>
					)}
					{canDeleteUser(currentUser, user) && (
						<Button danger onClick={() => handleDelete(user)}>
							Удалить
						</Button>
					)}
				</Space>
			),
		},
	]

	if (loading) {
		return <Preloader />
	}

	return (
		<>
			<Space style={{marginBottom: 16}}>
				<Input
					placeholder="Поиск"
					prefix={<SearchOutlined />}
					value={searchText}
					onChange={e => setSearchText(e.target.value)}
					allowClear
				/>
				<Button
					type="primary"
					onClick={() => {
						setSelectedUser(undefined)
						setModalVisible(true)
					}}
				>
					{'Добавить пользователя'}
				</Button>
			</Space>
			{!loading && (
				<Table
					columns={columns}
					dataSource={filteredData}
					rowKey="id"
					pagination={{
						current: pagination.current,
						pageSize: pagination.pageSize,
						total: filteredData.length,
						showSizeChanger: true,
						pageSizeOptions: ['5', '10', '20', '50'],
					}}
					onChange={handleTableChange}
				/>
			)}
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
