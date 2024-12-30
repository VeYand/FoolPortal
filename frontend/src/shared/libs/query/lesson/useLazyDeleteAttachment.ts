import {DeleteAttachmentRequest, EmptyResponse} from 'shared/api'
import {apiSlice, studentPortalApi} from 'shared/redux/api'

const api = apiSlice.injectEndpoints({
	endpoints: builder => ({
		deleteAttachment: builder.query<EmptyResponse, DeleteAttachmentRequest>({
			queryFn: async request => await studentPortalApi.get().lessonApi.deleteAttachment(request),
		}),
	}),
})

export const {useLazyDeleteAttachmentQuery: useLazyDeleteAttachment} = api
