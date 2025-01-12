import {SearchOutlined} from '@ant-design/icons'
import {Table, Button, Typography, Space, Input} from 'antd'
import {useState} from 'react'
import {Preloader} from '../preloader/Preloader'
import {Group, GroupDetailsModal} from './groupDetailsModal/GroupDetailsModal'
import {useInitialize} from './libs/useInitialize'

const GroupList = () => {
	const {loading, data, saveGroup, deleteGroup} = useInitialize()
	const [selectedGroup, setSelectedGroup] = useState<Group | undefined>()
	const [isModalOpen, setIsModalOpen] = useState(false)
	const [searchText, setSearchText] = useState('')

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

	const filteredData = data?.initialGroups.filter(group => {
		const searchString = `${group.name}`.toLowerCase()
		return searchString.includes(searchText.toLowerCase())
	})

	const columns = [
		{
			title: 'Название',
			dataIndex: 'name',
			key: 'name',
			sorter: (a: Group, b: Group) => a.name.localeCompare(b.name),
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
	]

	if (loading || !data) {
		return <Preloader/>
	}

	return (
		<div>
			<Typography.Title level={3}>{'Группы'}</Typography.Title>
			<Space style={{marginBottom: 16}}>
				<Input
					placeholder="Поиск"
					prefix={<SearchOutlined/>}
					value={searchText}
					onChange={e => setSearchText(e.target.value)}
					allowClear
				/>
				<Button type="primary" onClick={handleAddGroup}>
					{'Добавить группу'}
				</Button>
			</Space>
			<Table
				dataSource={filteredData}
				rowKey="id"
				columns={columns}
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