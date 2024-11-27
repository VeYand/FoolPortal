import {CoursesList, ListCoursesRequest} from 'shared/api'
import {apiSlice, studentPortalApi} from 'shared/redux/api'

const api = apiSlice.injectEndpoints({
	endpoints: builder => ({
		listCourses: builder.query<CoursesList, ListCoursesRequest>({
			queryFn: async request => await studentPortalApi.get().subjectApi.listCourses(request),
		}),
	}),
})

export const {useLazyListCoursesQuery: useLazyListCourses} = api
