import {Modal, Input, Typography} from 'antd'
import {useState} from 'react'
import {Student, StudentListForGroup} from './StudentListForGroup'
import {Subject, SubjectListForGroup, Teacher, TeacherSubject} from './SubjectListForGroup'

type Group = {
	id: string,
	name: string,
	studentIds: string[],
	teacherSubjectIds: string[],
}

type GroupDetailsModalProps = {
	group?: Group,
	availableStudents: Student[],
	availableTeachers: Teacher[],
	availableSubjects: Subject[],
	availableTeacherSubjects: TeacherSubject[],
	visible: boolean,
	onSave: (group: Group) => void,
	onClose: () => void,
}

const GroupDetailsModal = ({
	group,
	availableStudents,
	availableTeachers,
	availableSubjects,
	availableTeacherSubjects,
	visible,
	onSave,
	onClose,
}: GroupDetailsModalProps) => {
	const [name, setName] = useState(group?.name || '')
	const [studentIds, setStudentIds] = useState(group?.studentIds ?? [])
	const [teacherSubjectIds, setTeacherSubjectIds] = useState(group?.teacherSubjectIds ?? [])

	const addStudent = (studentId: string) => {
		setStudentIds([...studentIds, studentId])
	}

	const removeStudent = (studentId: string) => {
		setStudentIds(studentIds.filter(id => id !== studentId))
	}

	const addTeacherSubject = (teacherSubjectId: string) => {
		setTeacherSubjectIds([...teacherSubjectIds, teacherSubjectId])
	}

	const removeTeacherSubject = (teacherSubjectId: string) => {
		setTeacherSubjectIds(teacherSubjectIds.filter(id => id !== teacherSubjectId))
	}

	const handleSave = () => {
		onSave({
			id: group?.id ?? String(Date.now()),
			name,
			studentIds,
			teacherSubjectIds,
		})
	}

	return (
		<Modal
			title={group ? 'Редактировать группу' : 'Создать группу'}
			visible={visible}
			onOk={handleSave}
			onCancel={onClose}
			width={800}
		>
			<div>
				<Typography.Title level={4}>Название группы</Typography.Title>
				<Input
					value={name}
					onChange={e => setName(e.target.value)}
					placeholder="Введите название группы"
				/>
			</div>
			<StudentListForGroup
				availableStudents={availableStudents}
				addStudent={addStudent}
				removeStudent={removeStudent}
				selectedStudentIds={studentIds}
			/>
			<SubjectListForGroup
				availableSubjects={availableSubjects}
				availableTeacherSubjects={availableTeacherSubjects}
				availableTeachers={availableTeachers}
				selectedTeacherSubjectIds={teacherSubjectIds}
				addTeacherSubject={addTeacherSubject}
				removeTeacherSubject={removeTeacherSubject}
			/>
		</Modal>
	)
}

export {
	GroupDetailsModal,
	type Group,
}
