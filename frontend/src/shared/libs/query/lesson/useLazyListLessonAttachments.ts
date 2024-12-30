import {ListLessonAttachmentRequest, ListLessonAttachments200Response} from 'shared/api'
import {apiSlice, studentPortalApi} from 'shared/redux/api'

const api = apiSlice.injectEndpoints({
	endpoints: builder => ({
		listLessonAttachments: builder.query<ListLessonAttachments200Response, ListLessonAttachmentRequest>({
			queryFn: async request => await studentPortalApi.get().lessonApi.listLessonAttachments(request),
		}),
	}),
})

export const {useLazyListLessonAttachmentsQuery: useLazyListLessonAttachments} = api
