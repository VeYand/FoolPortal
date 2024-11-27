import {Table, Button, Modal, Select} from 'antd'
import {useState} from 'react'

type Subject = {
	id: string,
	name: string,
}

type Teacher = {
	id: string,
	name: string,
}

const availableSubjects: Subject[] = [
	{id: '3', name: 'History'},
	{id: '4', name: 'Chemistry'},
]

const teachers: Teacher[] = [
	{id: '1', name: 'Teacher A'},
	{id: '2', name: 'Teacher B'},
]

export const SubjectListForGroup = () => {
	const [subjects, setSubjects] = useState<Subject[]>([])
	const [isAddSubjectModalOpen, setIsAddSubjectModalOpen] = useState(false)
	const [selectedSubjectId, setSelectedSubjectId] = useState<string | null>(null)

	const handleAddSubject = () => {
		if (selectedSubjectId) {
			const subject = availableSubjects.find(s => s.id === selectedSubjectId)
			if (subject) {
				setSubjects([...subjects, subject])
			}
			setSelectedSubjectId(null)
			setIsAddSubjectModalOpen(false)
		}
	}

	const handleRemoveSubject = (id: string) => {
		setSubjects(subjects.filter(subject => subject.id !== id))
	}

	const handleSetTeacher = (subjectId: string, teacherId: string) => {
		setSubjects(
			subjects.map(subject =>
				(subject.id === subjectId ? {...subject, teacherId} : subject),
			),
		)
	}

	return (
		<div>
			<Button type="primary" onClick={() => setIsAddSubjectModalOpen(true)}>
				Добавить предмет
			</Button>
			<Table
				dataSource={subjects}
				rowKey="id"
				columns={[
					{
						title: 'Предмет',
						dataIndex: 'name',
						key: 'name',
					},
					{
						title: 'Преподаватель',
						key: 'teacher',
						render: (_: any, subject: Subject) => (
							<Select
								style={{width: 200}}
								placeholder="Выберите преподавателя"
								onChange={value => handleSetTeacher(subject.id, value)}
								allowClear
							>
								{teachers.map(teacher => (
									<Select.Option key={teacher.id} value={teacher.id}>
										{teacher.name}
									</Select.Option>
								))}
							</Select>
						),
					},
					{
						title: 'Действия',
						key: 'actions',
						render: (_: any, subject: Subject) => (
							<Button danger onClick={() => handleRemoveSubject(subject.id)}>
								Удалить
							</Button>
						),
					},
				]}
			/>

			<Modal
				title="Добавить предмет"
				visible={isAddSubjectModalOpen}
				onOk={handleAddSubject}
				onCancel={() => setIsAddSubjectModalOpen(false)}
			>
				<Select
					style={{width: '100%'}}
					placeholder="Выберите предмет"
					value={selectedSubjectId}
					onChange={value => setSelectedSubjectId(value)}
				>
					{availableSubjects.map(subject => (
						<Select.Option key={subject.id} value={subject.id}>
							{subject.name}
						</Select.Option>
					))}
				</Select>
			</Modal>
		</div>
	)
}
