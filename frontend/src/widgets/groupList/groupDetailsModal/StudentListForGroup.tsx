import {Table, Button, Modal, Select} from 'antd'
import {useState} from 'react'

type Student = {
	id: string,
	name: string,
}

type StudentListForGroupProps = {
	availableStudents: Student[],
	selectedStudentIds: string[],
	addStudent: (studentId: string) => void,
	removeStudent: (studentId: string) => void,
}

const StudentListForGroup = ({availableStudents, selectedStudentIds, addStudent, removeStudent}: StudentListForGroupProps) => {
	const [isAddUserModalOpen, setIsAddUserModalOpen] = useState(false)
	const [selectedUserId, setSelectedUserId] = useState<string | undefined>(undefined)

	const handleAddUser = () => {
		if (selectedUserId) {
			addStudent(selectedUserId)
			setSelectedUserId(undefined)
			setIsAddUserModalOpen(false)
		}
	}

	return (
		<div>
			<Button type="primary" onClick={() => setIsAddUserModalOpen(true)}>
				{'Добавить студента'}
			</Button>
			<Table
				dataSource={findStudentsByIds(availableStudents, selectedStudentIds)}
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
						render: (_: any, user: Student) => (
							<Button danger onClick={() => removeStudent(user.id)}>
								{'Удалить'}
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
					{availableStudents.map(user => (
						<Select.Option key={user.id} value={user.id}>
							{user.name}
						</Select.Option>
					))}
				</Select>
			</Modal>
		</div>
	)
}

const findStudentsByIds = (students: Student[], ids: string[]): Student[] => {
	const idSet = new Set(ids)
	return students.filter(student => idSet.has(student.id))
}

export {
	type Student,
	StudentListForGroup,
}
