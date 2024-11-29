import {DeleteGroupMembersRequest} from 'shared/api'
import {apiSlice, studentPortalApi} from 'shared/redux/api'

const api = apiSlice.injectEndpoints({
	endpoints: builder => ({
		deleteGroupMembers: builder.query<void, DeleteGroupMembersRequest>({
			queryFn: async request => await studentPortalApi.get().userApi.deleteGroupMembers(request),
		}),
	}),
})

export const {useLazyDeleteGroupMembersQuery: useLazyDeleteGroupMembers} = api