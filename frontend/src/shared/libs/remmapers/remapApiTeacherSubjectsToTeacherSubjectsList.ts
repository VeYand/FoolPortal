import {TeacherSubjectData as ApiTeacherSubjectData} from 'shared/api'
import {TeacherSubjectData} from '../../types'

const remapApiTeacherSubjectToTeacherSubjectData = (teacherSubject: ApiTeacherSubjectData): TeacherSubjectData => ({
	teacherSubjectId: teacherSubject.teacherSubjectId,
	teacherId: teacherSubject.teacherId,
	subjectId: teacherSubject.subjectId,
})

const remapApiTeacherSubjectsToTeacherSubjectsList = (teacherSubjects: ApiTeacherSubjectData[]): TeacherSubjectData[] => (
	teacherSubjects.map(remapApiTeacherSubjectToTeacherSubjectData)
)

export {
	remapApiTeacherSubjectsToTeacherSubjectsList,
}