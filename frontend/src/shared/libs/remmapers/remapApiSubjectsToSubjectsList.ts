import {SubjectData as ApiSubjectData} from 'shared/api'
import {SubjectData} from 'shared/types'

const remapApiSubjectToSubjectData = (subject: ApiSubjectData): SubjectData => ({
	subjectId: subject.subjectId,
	name: subject.name,
})

const remapApiSubjectsToSubjectsList = (subjects: ApiSubjectData[]): SubjectData[] => subjects.map(
	subject => remapApiSubjectToSubjectData(subject),
)

export {
	remapApiSubjectsToSubjectsList,
}