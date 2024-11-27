import {Table, Button, Modal, Select} from 'antd'
import {useState} from 'react'

type User = {
	id: string,
	name: string,
}

const availableUsers: User[] = [
	{id: '3', name: 'User C'},
	{id: '4', name: 'User D'},
]

export const UserListForGroup = () => {
	const [users, setUsers] = useState<User[]>([])
	const [isAddUserModalOpen, setIsAddUserModalOpen] = useState(false)
	const [selectedUserId, setSelectedUserId] = useState<string | null>(null)

	const handleAddUser = () => {
		if (selectedUserId) {
			const user = availableUsers.find(u => u.id === selectedUserId)
			if (user) {
				setUsers([...users, user])
			}
			setSelectedUserId(null)
			setIsAddUserModalOpen(false)
		}
	}

	const handleRemoveUser = (id: string) => {
		setUsers(users.filter(user => user.id !== id))
	}

	return (
		<div>
			<Button type="primary" onClick={() => setIsAddUserModalOpen(true)}>
				Добавить пользователя
			</Button>
			<Table
				dataSource={users}
				rowKey="id"
				columns={[
					{
						title: 'Пользователь',
						dataIndex: 'name',
						key: 'name',
					},
					{
						title: 'Действия',
						key: 'actions',
						render: (_: any, user: User) => (
							<Button danger onClick={() => handleRemoveUser(user.id)}>
								Удалить
							</Button>
						),
					},
				]}
			/>

			<Modal
				title="Добавить пользователя"
				visible={isAddUserModalOpen}
				onOk={handleAddUser}
				onCancel={() => setIsAddUserModalOpen(false)}
			>
				<Select
					style={{width: '100%'}}
					placeholder="Выберите пользователя"
					value={selectedUserId}
					onChange={value => setSelectedUserId(value)}
				>
					{availableUsers.map(user => (
						<Select.Option key={user.id} value={user.id}>
							{user.name}
						</Select.Option>
					))}
				</Select>
			</Modal>
		</div>
	)
}
