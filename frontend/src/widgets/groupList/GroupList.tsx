import {Table, Button, Typography} from 'antd'
import {useState} from 'react'
import {Preloader} from '../preloader/Preloader'
import {Group, GroupDetailsModal} from './groupDetailsModal/GroupDetailsModal'
import {useInitialize} from './libs/useInitialize'

const GroupList = () => {
	const {loading, data, saveGroup, deleteGroup} = useInitialize()
	const [selectedGroup, setSelectedGroup] = useState<Group | undefined>()
	const [isModalOpen, setIsModalOpen] = useState(false)

	const handleEditGroup = (group: Group) => {
		setSelectedGroup(group)
		setIsModalOpen(true)
	}

	const handleAddGroup = () => {
		setSelectedGroup(undefined)
		setIsModalOpen(true)
	}

	const handleDeleteGroup = (id: string) => {
		deleteGroup(id)
	}

	const handleSaveGroup = (updatedGroup: Group) => {
		saveGroup(updatedGroup)
		setIsModalOpen(false)
	}

	if (loading || !data) {
		return <Preloader/>
	}

	return (
		<div>
			<Typography.Title level={3}>Группы</Typography.Title>
			<Button type="primary" onClick={handleAddGroup}>
				{'Добавить группу'}
			</Button>
			<Table
				dataSource={data.initialGroups}
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
							<div style={{display: 'flex', gap: 10}}>
								<Button onClick={() => handleEditGroup(group)}>
									{'Редактировать'}
								</Button>
								<Button onClick={() => handleDeleteGroup(group.id)} danger>
									{'Удалить'}
								</Button>
							</div>
						),
					},
				]}
			/>
			<GroupDetailsModal
				group={selectedGroup}
				availableStudents={data.availableStudents}
				availableTeachers={data.availableTeachers}
				availableSubjects={data.availableSubjects}
				availableTeacherSubjects={data.availableTeacherSubjects}
				visible={isModalOpen}
				onSave={handleSaveGroup}
				onClose={() => setIsModalOpen(false)}
			/>
		</div>
	)
}

export {
	GroupList,
}