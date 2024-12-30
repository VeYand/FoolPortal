import {CoursesList as ApiCoursesList, ListCoursesRequest as ApiListCoursesRequest} from 'shared/api'
import {apiSlice, studentPortalApi} from 'shared/redux/api'

const api = apiSlice.injectEndpoints({
	endpoints: builder => ({
		listCourses: builder.query<ApiCoursesList, ApiListCoursesRequest>({
			queryFn: async request => await studentPortalApi.get().subjectApi.listCourses(request),
		}),
	}),
})

export const {useLazyListCoursesQuery: useLazyListCourses} = api
