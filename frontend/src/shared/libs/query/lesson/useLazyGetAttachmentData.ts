import {GetAttachment200Response, GetAttachmentRequest} from 'shared/api'
import {apiSlice, studentPortalApi} from 'shared/redux/api'

const api = apiSlice.injectEndpoints({
	endpoints: builder => ({
		getAttachmentData: builder.query<GetAttachment200Response, GetAttachmentRequest>({
			queryFn: async request => await studentPortalApi.get().lessonApi.getAttachment(request),
		}),
	}),
})

export const {useLazyGetAttachmentDataQuery: useLazyGetAttachmentData} = api
