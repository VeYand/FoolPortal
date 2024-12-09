import {CreateCoursesRequest, EmptyResponse} from 'shared/api'
import {apiSlice, studentPortalApi} from 'shared/redux/api'

const api = apiSlice.injectEndpoints({
	endpoints: builder => ({
		createCourses: builder.query<EmptyResponse, CreateCoursesRequest>({
			queryFn: async request => await studentPortalApi.get().subjectApi.createCourses(request),
		}),
	}),
})

export const {useLazyCreateCoursesQuery: useLazyCreateCourses} = api
