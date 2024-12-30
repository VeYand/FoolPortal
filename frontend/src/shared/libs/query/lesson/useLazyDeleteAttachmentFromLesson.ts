import {DeleteAttachmentFromLessonRequest, EmptyResponse} from 'shared/api'
import {apiSlice, studentPortalApi} from 'shared/redux/api'

const api = apiSlice.injectEndpoints({
	endpoints: builder => ({
		deleteAttachmentFromLesson: builder.query<EmptyResponse, DeleteAttachmentFromLessonRequest>({
			queryFn: async request => await studentPortalApi.get().lessonApi.deleteAttachmentFromLesson(request),
		}),
	}),
})

export const {useLazyDeleteAttachmentFromLessonQuery: useLazyDeleteAttachmentFromLesson} = api
