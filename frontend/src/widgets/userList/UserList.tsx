import {SearchOutlined} from '@ant-design/icons'
import {Avatar, Button, Input, Space, Table} from 'antd'
import {SortOrder} from 'antd/lib/table/interface'
import {useState} from 'react'
import {getViewableUserRole} from 'shared/libs'
import {useAppSelector} from 'shared/redux'
import {USER_ROLE, UserData} from 'shared/types'
import {RoleTag} from 'shared/ui/RoleTag/RoleTag'
import {Preloader} from '../preloader/Preloader'
import {DeleteConfirmationModal} from './DeleteConfirmationModal'
import {canDeleteUser, canModifyUser} from './libs/canModifyUser'
import {OrderField, Sort, useInitialize} from './libs/useInitialize'
import {UserFormModal} from './UserFormModal'


const UserList = () => {
	const [pagination, setPagination] = useState({
		current: 1,
		pageSize: 10,
	})
	const [sortOrder, setSortOrder] = useState<Sort | undefined>()
	const [sortField, setSortField] = useState<OrderField | undefined>()
	const [filterRoles, setFilterRoles] = useState<USER_ROLE[] | undefined>()

	const {
		users,
		groups,
		subjects,
		teacherSubjects,
		saveUser,
		deleteUser,
		maxPage,
		loading,
	} = useInitialize(sortOrder, sortField, pagination.current, pagination.pageSize, filterRoles)

	const currentUser = useAppSelector(state => state.userEntity.user)
	const [selectedUser, setSelectedUser] = useState<UserData | undefined>()
	const [isModalVisible, setModalVisible] = useState(false)
	const [isDeleteModalVisible, setDeleteModalVisible] = useState(false)
	const [searchText, setSearchText] = useState('')

	const handleTableChange = (paginationData: any, filters: any, sorter: any) => {
		const newSortOrder = sorter.order ? (sorter.order === 'ascend' ? 'ASC' : 'DESC') : undefined
		const newSortField = sorter.field

		setPagination({
			current: paginationData.current,
			pageSize: paginationData.pageSize,
		})
		setSortOrder(newSortOrder)
		setSortField(newSortField)
		setFilterRoles(filters.role)
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
			sorter: true,
			sortOrder: (sortField === 'name' ? (sortOrder === 'ASC' ? 'ascend' : 'descend') : null) as SortOrder,
			render: (_: string, user: UserData) =>
				`${user.firstName} ${user.lastName} ${user.patronymic || ''}`,
		},
		{
			title: 'Email',
			dataIndex: 'email',
			key: 'email',
			sorter: true,
			sortOrder: (sortField === 'email' ? (sortOrder === 'ASC' ? 'ascend' : 'descend') : null) as SortOrder,
		},
		{
			title: 'Роль',
			dataIndex: 'role',
			key: 'role',
			filters: Object.values(USER_ROLE).map(role => ({
				text: getViewableUserRole(role),
				value: role,
			})),
			filteredValue: filterRoles,
			onFilter: (value: any, record: UserData) => record.role === value,
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

	return (
		<>
			<Space style={{marginBottom: 16}}>
				<Input
					placeholder="Поиск"
					prefix={<SearchOutlined/>}
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
			{loading && <Preloader/>}
			{!loading && (
				<Table
					columns={columns}
					dataSource={filteredData}
					rowKey="id"
					pagination={{
						current: pagination.current,
						pageSize: pagination.pageSize,
						total: maxPage * pagination.pageSize,
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
