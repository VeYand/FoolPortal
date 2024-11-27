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

type SubjectTeacherTagsProps = {
	subject: Subject,
	teachers: Teacher[],
	removeTeacherSubject: (teacherId: string, subjectId: string) => void,
}

const SubjectTeacherTags = ({subject, teachers, removeTeacherSubject}: SubjectTeacherTagsProps) => (
	<div>
		{teachers.map(teacher => (
			<Tag
				closable
				key={teacher?.id}
				onClose={() => removeTeacherSubject(teacher.id, subject.id)}
			>
				{teacher?.name}
			</Tag>
		))}
	</div>
)

const SubjectListForGroup = ({
	                             availableSubjects,
	                             availableTeachers,
	                             availableTeacherSubjects,
	                             selectedTeacherSubjectIds,
	                             addTeacherSubject,
	                             removeTeacherSubject,
}: SubjectListForGroupProps) => {
	const [isAddTeacherSubjectsModalOpen, setIsAddTeacherSubjectsModalOpen] = useState(false)
	const [selectedSubjectId, setSelectedSubjectId] = useState<string | undefined>()
	const [selectedTeacherIds, setSelectedTeacherIds] = useState<string[]>([])

	const resetModalState = () => {
		setSelectedSubjectId(undefined)
		setSelectedTeacherIds([])
		setIsAddTeacherSubjectsModalOpen(false)
	}

	const handleAddTeacherSubjects = () => {
		if (selectedSubjectId && selectedTeacherIds.length > 0) {
			selectedTeacherIds.forEach(teacherId => {
				const teacherSubject = findOrCreateTeacherSubject(
					availableTeacherSubjects,
					teacherId,
					selectedSubjectId,
				)
				addTeacherSubject(teacherSubject.teacherSubjectId)
			})
			resetModalState()
		}
	}

	const handleCancelAddTeacherSubjects = () => {
		resetModalState()
	}

	const removeTeacherSubjectByTeacherAndSubject = (teacherId: string, subjectId: string) => {
		const teacherSubject = findTeacherSubject(availableTeacherSubjects, teacherId, subjectId)
		if (teacherSubject) {
			removeTeacherSubject(teacherSubject.teacherSubjectId)
		}
	}

	const removeTeacherSubjectBySubject = (subjectId: string) => {
		const teacherSubjects = findTeacherSubjectsBySubject(availableTeacherSubjects, subjectId)
		for (const teacherSubject of teacherSubjects) {
			removeTeacherSubject(teacherSubject.teacherSubjectId)
		}
	}

	return (
		<div>
			<Button type="primary" onClick={() => setIsAddTeacherSubjectsModalOpen(true)}>
				{'Добавить предмет'}
			</Button>
			<Table
				dataSource={findSubjectsByTeacherSubjectIds(
					availableSubjects,
					availableTeacherSubjects,
					selectedTeacherSubjectIds,
				)}
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
						render: (_: any, subject: Subject) => (
							<div style={{display: 'flex', justifyContent: 'space-between'}}>
								<SubjectTeacherTags
									subject={subject}
									teachers={findTeachersBySubject(availableTeachers, availableTeacherSubjects, subject.id)}
									removeTeacherSubject={removeTeacherSubjectByTeacherAndSubject}
								/>
								<Button danger onClick={() => removeTeacherSubjectBySubject(subject.id)}>
									{'Удалить'}
								</Button>
							</div>
						),
					},
				]}
			/>

			<Modal
				title="Добавить предмет к группе"
				visible={isAddTeacherSubjectsModalOpen}
				onOk={handleAddTeacherSubjects}
				onCancel={handleCancelAddTeacherSubjects}
			>
				<Select
					style={{width: '100%', marginBottom: 16}}
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

				{selectedSubjectId && (
					<Select
						mode="multiple"
						style={{width: '100%'}}
						placeholder="Выберите преподавателей"
						value={selectedTeacherIds}
						onChange={value => setSelectedTeacherIds(value)}
					>
						{findTeachersBySubject(availableTeachers, availableTeacherSubjects, selectedSubjectId).map(
							teacher => (
								<Select.Option key={teacher.id} value={teacher.id}>
									{teacher.name}
								</Select.Option>
							),
						)}
					</Select>
				)}
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
	const selectedTeacherSubjects = teacherSubjects.filter(teacherSubject =>
		idSet.has(teacherSubject.teacherSubjectId),
	)
	const selectedSubjectIds = new Set(
		selectedTeacherSubjects.map(teacherSubject => teacherSubject.subjectId),
	)
	return subjects.filter(subject => selectedSubjectIds.has(subject.id))
}

const findTeachersBySubject = (
	teachers: Teacher[],
	teacherSubjects: TeacherSubject[],
	subjectId: string,
): Teacher[] => {
	const teacherSubjectsBySubject = teacherSubjects.filter(
		teacherSubject => teacherSubject.subjectId === subjectId,
	)
	const teacherIds = new Set(
		teacherSubjectsBySubject.map(teacherSubject => teacherSubject.teacherId),
	)
	return teachers.filter(teacher => teacherIds.has(teacher.id))
}

const findTeacherSubject = (
	teacherSubjects: TeacherSubject[],
	teacherId: string,
	subjectId: string,
): TeacherSubject | undefined => (
	teacherSubjects.find(
		ts => ts.subjectId === subjectId && ts.teacherId === teacherId,
	)
)

const findTeacherSubjectsBySubject = (
	teacherSubjects: TeacherSubject[],
	subjectId: string,
): TeacherSubject[] => (
	teacherSubjects.filter(
		ts => ts.subjectId === subjectId,
	)
)

const findOrCreateTeacherSubject = (
	teacherSubjects: TeacherSubject[],
	teacherId: string,
	subjectId: string,
): TeacherSubject => {
	const existing = teacherSubjects.find(
		ts => ts.subjectId === subjectId && ts.teacherId === teacherId,
	)
	return (
		existing || {
			teacherSubjectId: `${subjectId}-${teacherId}`,
			teacherId,
			subjectId,
		}
	)
}

export {
	SubjectListForGroup,
	type TeacherSubject,
	type Teacher,
	type Subject,
}
