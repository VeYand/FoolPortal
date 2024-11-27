import {Table, Button, Typography} from 'antd'
import {useState} from 'react'
import {GroupDetailsModal} from './groupDetailsModal/GroupDetailsModal'
import styles from './GroupList.module.css'

type Group = {
	id: string,
	name: string,
}

const initialGroups: Group[] = [
	{id: '1', name: 'Group A'},
	{id: '2', name: 'Group B'},
]

export const GroupList = () => {
	const [groups, setGroups] = useState<Group[]>(initialGroups)
	const [selectedGroup, setSelectedGroup] = useState<Group | null>(null)
	const [isModalOpen, setIsModalOpen] = useState(false)

	const handleEditGroup = (group: Group) => {
		setSelectedGroup(group)
		setIsModalOpen(true)
	}

	const handleAddGroup = () => {
		setSelectedGroup(null)
		setIsModalOpen(true)
	}

	const handleDeleteGroup = (id: string) => {
		setGroups(groups.filter(group => group.id !== id))
	}

	const handleSaveGroup = (updatedGroup: Group) => {
		setGroups(prev =>
			(prev.some(group => group.id === updatedGroup.id)
				? prev.map(group => (group.id === updatedGroup.id ? updatedGroup : group))
				: [...prev, updatedGroup]),
		)
		setIsModalOpen(false)
	}

	return (
		<div className={styles.container}>
			<Typography.Title level={3}>Группы</Typography.Title>
			<Button type="primary" onClick={handleAddGroup} className={styles.addButton}>
				Добавить группу
			</Button>
			<Table
				dataSource={groups}
				rowKey="id"
				columns={[
					{
						title: 'Название',
						dataIndex: 'name',
						key: 'name',
					},
					{
						title: 'Действия',
						key: 'actions',
						render: (_: any, group: Group) => (
							<>
								<Button onClick={() => handleEditGroup(group)} type="link">
									Редактировать
								</Button>
								<Button onClick={() => handleDeleteGroup(group.id)} danger type="link">
									Удалить
								</Button>
							</>
						),
					},
				]}
			/>
			<GroupDetailsModal
				group={selectedGroup}
				visible={isModalOpen}
				onSave={handleSaveGroup}
				onClose={() => setIsModalOpen(false)}
			/>
		</div>
	)
}
