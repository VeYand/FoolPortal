import {GroupData as ApiGroupData} from 'shared/api'
import {GroupData} from 'shared/types'

const remapApiGroupToGroupData = (group: ApiGroupData): GroupData => ({
	groupId: group.groupId,
	name: group.name,
})

const remapApiGroupsToGroupsList = (groups: ApiGroupData[]): GroupData[] => (
	groups.map(remapApiGroupToGroupData, groups)
)

export {
	remapApiGroupsToGroupsList,
}