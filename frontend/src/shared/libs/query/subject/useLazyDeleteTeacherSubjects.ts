import {DeleteTeacherSubjectsRequest, EmptyResponse} from 'shared/api'
import {apiSlice, studentPortalApi} from 'shared/redux/api'

const api = apiSlice.injectEndpoints({
	endpoints: builder => ({
		deleteTeacherSubjects: builder.query<EmptyResponse, DeleteTeacherSubjectsRequest>({
			queryFn: async request => await studentPortalApi.get().subjectApi.deleteTeacherSubjects(request),
		}),
	}),
})

export const {useLazyDeleteTeacherSubjectsQuery: useLazyDeleteTeacherSubjects} = api
