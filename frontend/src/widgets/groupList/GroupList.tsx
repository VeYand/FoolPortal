import {Table, Button, Typography} from 'antd'
import {useState} from 'react'
import {Preloader} from '../preloader/Preloader'
import {Group, GroupDetailsModal} from './groupDetailsModal/GroupDetailsModal'
import {useInitialize} from './libs/useInitialize'

// const availableStudents = [
// 	{id: '1', name: 'Nikita Test'},
// 	{id: '2', name: 'Vasiliy Chehov'},
// 	{id: '3', name: 'Gosha Dudar Ivanovich'},
// 	{id: '4', name: 'Qwerty Mili'},
// ]
// const availableTeachers = [
// 	{id: '5', name: 'Преподаватель Умный'},
// 	{id: '6', name: 'Преподаватель Красивый'},
// ]
// const availableSubjects = [
// 	{id: '1', name: 'OOD'},
// 	{id: '2', name: 'MLITA'},
// 	{id: '3', name: 'Physics'},
// 	{id: '4', name: 'Math'},
// ]
// const availableTeacherSubjects = [
// 	{teacherSubjectId: '1', subjectId: '1', teacherId: '5'},
// 	{teacherSubjectId: '2', subjectId: '2', teacherId: '5'},
// 	{teacherSubjectId: '3', subjectId: '3', teacherId: '5'},
// 	{teacherSubjectId: '4', subjectId: '1', teacherId: '6'},
// ]
// const initialGroups: Group[] = [
// 	{id: '1', name: 'Group A', studentIds: [], teacherSubjectIds: []},
// 	{id: '2', name: 'Group B', studentIds: [], teacherSubjectIds: []},
// ]


const GroupList = () => {
	const {loading, data} = useInitialize()

	// const [groups, setGroups] = useState<Group[]>(initialGroups)
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
		console.log('delete', id)
		// setGroups(groups.filter(group => group.id !== id))
	}

	const handleSaveGroup = (updatedGroup: Group) => {
		console.log('save', updatedGroup.id)
		// setGroups(prev =>
		// 	(prev.some(group => group.id === updatedGroup.id)
		// 		? prev.map(group => (group.id === updatedGroup.id ? updatedGroup : group))
		// 		: [...prev, updatedGroup]),
		// )
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
							<>
								<Button onClick={() => handleEditGroup(group)} type="link">
									{'Редактировать'}
								</Button>
								<Button onClick={() => handleDeleteGroup(group.id)} danger type="link">
									{'Удалить'}
								</Button>
							</>
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