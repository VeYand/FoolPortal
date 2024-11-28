import {apiSlice, studentPortalApi} from 'shared/redux/api'

const api = apiSlice.injectEndpoints({
	endpoints: builder => ({
		listTeacherSubjects: builder.query({
			queryFn: async () => await studentPortalApi.get().subjectApi.listTeacherSubjects(),
		}),
	}),
})

export const {useLazyListTeacherSubjectsQuery: useLazyListTeacherSubjects} = api
