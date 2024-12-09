import {CreateSubjectRequest, EmptyResponse} from 'shared/api'
import {apiSlice, studentPortalApi} from 'shared/redux/api'

const api = apiSlice.injectEndpoints({
	endpoints: builder => ({
		createSubject: builder.query<EmptyResponse, CreateSubjectRequest>({
			queryFn: async request => await studentPortalApi.get().subjectApi.createSubject(request),
		}),
	}),
})

export const {useLazyCreateSubjectQuery: useLazyCreateSubject} = api
