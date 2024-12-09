import {DeleteCoursesRequest, EmptyResponse} from 'shared/api'
import {apiSlice, studentPortalApi} from 'shared/redux/api'

const api = apiSlice.injectEndpoints({
	endpoints: builder => ({
		deleteCourses: builder.query<EmptyResponse, DeleteCoursesRequest>({
			queryFn: async request => await studentPortalApi.get().subjectApi.deleteCourses(request),
		}),
	}),
})

export const {useLazyDeleteCoursesQuery: useLazyDeleteCourses} = api
