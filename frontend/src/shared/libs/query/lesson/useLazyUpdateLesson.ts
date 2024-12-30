import {EmptyResponse, UpdateLessonRequest} from 'shared/api'
import {apiSlice, studentPortalApi} from 'shared/redux/api'

const api = apiSlice.injectEndpoints({
	endpoints: builder => ({
		updateLesson: builder.query<EmptyResponse, UpdateLessonRequest>({
			queryFn: async request => await studentPortalApi.get().lessonApi.updateLesson(request),
		}),
	}),
})

export const {useLazyUpdateLessonQuery: useLazyUpdateLesson} = api
