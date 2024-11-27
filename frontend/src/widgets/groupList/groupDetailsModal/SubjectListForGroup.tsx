import {Table, Button, Modal, Select, Tag} from 'antd'
import {useState} from 'react'

type Subject = {
	id: string,
	name: string,
}

type Teacher = {
	id: string,
	name: string,
}

type TeacherSubject = {
	teacherSubjectId: string,
	teacherId: string,
	subjectId: string,
}

type SubjectListForGroupProps = {
	availableSubjects: Subject[],
	availableTeachers: Teacher[],
	availableTeacherSubjects: TeacherSubject[],
	selectedTeacherSubjectIds: string[],
	addTeacherSubject: (teacherSubjectId: string) => void,
	removeTeacherSubject: (teacherSubjectId: string) => void,
}

const SubjectListForGroup = ({
	availableSubjects,
	availableTeachers,
	availableTeacherSubjects,
	selectedTeacherSubjectIds,
	addTeacherSubject,
	removeTeacherSubject,
}: SubjectListForGroupProps) => {
	const [isAddSubjectModalOpen, setIsAddSubjectModalOpen] = useState(false)
	const [selectedSubjectId, setSelectedSubjectId] = useState<string | undefined>()

	const handleAddTeacher = (subjectId: string, teacherId: string) => {
		const teacherSubject = findTeacherSubject(availableTeacherSubjects, teacherId, subjectId)
		if (teacherSubject && !selectedTeacherSubjectIds.includes(teacherSubject.teacherSubjectId)) {
			addTeacherSubject(teacherSubject.teacherSubjectId)
		}
	}

	const handleRemoveTeacher = (teacherSubjectId: string) => {
		removeTeacherSubject(teacherSubjectId)
	}

	const handleAddSubject = () => {
		if (selectedSubjectId) {
			setIsAddSubjectModalOpen(false)
			setSelectedSubjectId(undefined)
		}
	}

	return (
		<div>
			<Button type="primary" onClick={() => setIsAddSubjectModalOpen(true)}>
				{'Добавить предмет'}
			</Button>
			<Table
				dataSource={findSubjectsByTeacherSubjectIds(availableSubjects, availableTeacherSubjects, selectedTeacherSubjectIds)}
				rowKey="id"
				columns={[
					{
						title: 'Предмет',
						dataIndex: 'name',
						key: 'name',
					},
					{
						title: 'Преподаватели',
						key: 'teachers',
						render: (_: any, subject: Subject) => {
							const subjectTeacherSubjects = availableTeacherSubjects.filter(
								ts => ts.subjectId === subject.id && selectedTeacherSubjectIds.includes(ts.teacherSubjectId),
							)

							const subjectTeachers = subjectTeacherSubjects.map(ts =>
								availableTeachers.find(t => t.id === ts.teacherId),
							)

							return (
								<div>
									{subjectTeachers.map(teacher => (
										<Tag
											closable
											key={teacher?.id}
											onClose={() => handleRemoveTeacher(subjectTeacherSubjects.find(ts => ts.teacherId === teacher?.id)?.teacherSubjectId || '')}
										>
											{teacher?.name}
										</Tag>
									))}
									<Select
										style={{width: 200}}
										placeholder="Добавить преподавателя"
										onChange={value => handleAddTeacher(subject.id, value)}
										allowClear
									>
										{findTeachersBySubject(availableTeachers, availableTeacherSubjects, subject.id).map(teacher => (
											<Select.Option key={teacher.id} value={teacher.id}>
												{teacher.name}
											</Select.Option>
										))}
									</Select>
								</div>
							)
						},
					},
					{
						title: 'Действия',
						key: 'actions',
						render: (_: any, subject: Subject) => (
							<Button danger onClick={() => removeTeacherSubjectsBySubject(
								subject.id,
								selectedTeacherSubjectIds,
								availableTeacherSubjects,
								handleRemoveTeacher,
							)}>
								{'Удалить'}
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

const findSubjectsByTeacherSubjectIds = (
	subjects: Subject[],
	teacherSubjects: TeacherSubject[],
	teacherSubjectIds: string[],
): Subject[] => {
	const idSet = new Set(teacherSubjectIds)
	const selectedTeacherSubjects = teacherSubjects.filter(teacherSubject => idSet.has(teacherSubject.teacherSubjectId))
	const selectedSubjectIds = new Set(selectedTeacherSubjects.map(teacherSubject => teacherSubject.subjectId))
	return subjects.filter(subject => selectedSubjectIds.has(subject.id))
}

const findTeachersBySubject = (
	teachers: Teacher[],
	teacherSubjects: TeacherSubject[],
	subjectId: string,
): Teacher[] => {
	const teacherSubjectsBySubject = teacherSubjects.filter(teacherSubject => teacherSubject.subjectId === subjectId)
	const teacherIds = new Set(teacherSubjectsBySubject.map(teacherSubject => teacherSubject.teacherId))
	return teachers.filter(teacher => teacherIds.has(teacher.id))
}

const findTeacherSubject = (
	teacherSubjects: TeacherSubject[],
	teacherId: string,
	subjectId: string,
): TeacherSubject | undefined => teacherSubjects.find(
	teacherSubject => teacherSubject.subjectId === subjectId && teacherSubject.teacherId === teacherId,
)

const removeTeacherSubjectsBySubject = (
	subjectId: string,
	selectedTeacherSubjectIds: string[],
	teacherSubjects: TeacherSubject[],
	removeTeacherSubject: (teacherSubjectId: string) => void,
): void => {
	const subjectTeacherSubjects = teacherSubjects.filter(ts => ts.subjectId === subjectId)
	subjectTeacherSubjects.forEach(ts => {
		if (selectedTeacherSubjectIds.includes(ts.teacherSubjectId)) {
			removeTeacherSubject(ts.teacherSubjectId)
		}
	})
}

export {
	SubjectListForGroup,
	type TeacherSubject,
	type Teacher,
	type Subject,
}
