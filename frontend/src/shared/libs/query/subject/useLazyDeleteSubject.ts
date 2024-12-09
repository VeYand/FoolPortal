import {DeleteSubjectRequest, EmptyResponse} from 'shared/api'
import {apiSlice, studentPortalApi} from 'shared/redux/api'

const api = apiSlice.injectEndpoints({
	endpoints: builder => ({
		deleteSubject: builder.query<EmptyResponse, DeleteSubjectRequest>({
			queryFn: async request => await studentPortalApi.get().subjectApi.deleteSubject(request),
		}),
	}),
})

export const {useLazyDeleteSubjectQuery: useLazyDeleteSubject} = api
