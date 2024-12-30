import {CreateLesson200Response, CreateLessonRequest} from 'shared/api'
import {apiSlice, studentPortalApi} from 'shared/redux/api'

const api = apiSlice.injectEndpoints({
	endpoints: builder => ({
		createLesson: builder.query<CreateLesson200Response, CreateLessonRequest>({
			queryFn: async request => await studentPortalApi.get().lessonApi.createLesson(request),
		}),
	}),
})

export const {useLazyCreateLessonQuery: useLazyCreateLesson} = api
