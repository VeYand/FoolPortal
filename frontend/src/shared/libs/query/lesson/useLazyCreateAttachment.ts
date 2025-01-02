import {CreateAttachment200Response, CreateAttachmentRequest} from 'shared/api'
import {apiSlice, studentPortalApi} from 'shared/redux/api'

const api = apiSlice.injectEndpoints({
	endpoints: builder => ({
		createAttachment: builder.query<CreateAttachment200Response, CreateAttachmentRequest>({
			queryFn: async request => await studentPortalApi.get().lessonApi.createAttachment(request),
		}),
	}),
})

export const {useLazyCreateAttachmentQuery: useLazyCreateAttachment} = api
