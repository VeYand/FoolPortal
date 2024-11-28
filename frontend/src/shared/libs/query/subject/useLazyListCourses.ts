import {apiSlice, studentPortalApi} from 'shared/redux/api'

const api = apiSlice.injectEndpoints({
	endpoints: builder => ({
		listCourses: builder.query({
			queryFn: async () => await studentPortalApi.get().subjectApi.listCourses(),
		}),
	}),
})

export const {useLazyListCoursesQuery: useLazyListCourses} = api
