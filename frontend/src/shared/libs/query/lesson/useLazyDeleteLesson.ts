import {DeleteLessonRequest, EmptyResponse} from 'shared/api'
import {apiSlice, studentPortalApi} from 'shared/redux/api'

const api = apiSlice.injectEndpoints({
	endpoints: builder => ({
		deleteLesson: builder.query<EmptyResponse, DeleteLessonRequest>({
			queryFn: async request => await studentPortalApi.get().lessonApi.deleteLesson(request),
		}),
	}),
})

export const {useLazyDeleteLessonQuery: useLazyDeleteLesson} = api
