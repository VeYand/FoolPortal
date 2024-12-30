import {ListTeacherSubjectsRequest, TeacherSubjectsList as ApiTeacherSubjectsList} from 'shared/api'
import {apiSlice, studentPortalApi} from 'shared/redux/api'

const api = apiSlice.injectEndpoints({
	endpoints: builder => ({
		listTeacherSubjects: builder.query<ApiTeacherSubjectsList, ListTeacherSubjectsRequest>({
			queryFn: async request => await studentPortalApi.get().subjectApi.listTeacherSubjects(request),
		}),
	}),
})

export const {useLazyListTeacherSubjectsQuery: useLazyListTeacherSubjects} = api
