import {UpdateSubjectRequest} from 'shared/api'
import {apiSlice, studentPortalApi} from 'shared/redux/api'

const api = apiSlice.injectEndpoints({
	endpoints: builder => ({
		updateSubject: builder.query<void, UpdateSubjectRequest>({
			queryFn: async request => await studentPortalApi.get().subjectApi.updateSubject(request),
		}),
	}),
})

export const {useLazyUpdateSubjectQuery: useLazyUpdateSubject} = api
