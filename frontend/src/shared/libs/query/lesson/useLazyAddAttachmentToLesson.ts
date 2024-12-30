import {AddAttachmentToLessonRequest, EmptyResponse} from 'shared/api'
import {apiSlice, studentPortalApi} from 'shared/redux/api'

const api = apiSlice.injectEndpoints({
	endpoints: builder => ({
		addAttachmentToLesson: builder.query<EmptyResponse, AddAttachmentToLessonRequest>({
			queryFn: async request => await studentPortalApi.get().lessonApi.addAttachmentToLesson(request),
		}),
	}),
})

export const {useLazyAddAttachmentToLessonQuery: useLazyAddAttachmentToLesson} = api
