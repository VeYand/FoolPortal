import {apiSlice, studentPortalApi} from 'shared/redux/api'

const api = apiSlice.injectEndpoints({
	endpoints: builder => ({
		listSubjects: builder.query({
			queryFn: async () => await studentPortalApi.get().subjectApi.listSubjects(),
		}),
	}),
})

export const {useLazyListSubjectsQuery: useLazyListSubjects} = api
