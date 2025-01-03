import {CreateTeacherSubjectsRequest, EmptyResponse} from 'shared/api'
import {apiSlice, studentPortalApi} from 'shared/redux/api'

const api = apiSlice.injectEndpoints({
	endpoints: builder => ({
		createTeacherSubjects: builder.query<EmptyResponse, CreateTeacherSubjectsRequest>({
			queryFn: async request => await studentPortalApi.get().subjectApi.createTeacherSubjects(request),
		}),
	}),
})

export const {useLazyCreateTeacherSubjectsQuery: useLazyCreateTeacherSubjects} = api
