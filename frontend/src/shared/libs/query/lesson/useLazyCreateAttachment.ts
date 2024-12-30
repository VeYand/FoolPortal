import {AttachmentData, CreateAttachment200Response} from 'shared/api'
import {apiSlice, studentPortalApi} from 'shared/redux/api'

const api = apiSlice.injectEndpoints({
	endpoints: builder => ({
		createAttachment: builder.query<CreateAttachment200Response, AttachmentData>({
			queryFn: async request => await studentPortalApi.get().lessonApi.createAttachment(request),
		}),
	}),
})

export const {useLazyCreateAttachmentQuery: useLazyCreateAttachment} = api
