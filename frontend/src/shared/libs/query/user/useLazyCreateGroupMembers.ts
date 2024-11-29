import {CreateGroupMembersRequest} from 'shared/api'
import {apiSlice, studentPortalApi} from 'shared/redux/api'

const api = apiSlice.injectEndpoints({
	endpoints: builder => ({
		createGroupMembers: builder.query<void, CreateGroupMembersRequest>({
			queryFn: async request => await studentPortalApi.get().userApi.createGroupMembers(request),
		}),
	}),
})

export const {useLazyCreateGroupMembersQuery: useLazyCreateGroupMembers} = api
