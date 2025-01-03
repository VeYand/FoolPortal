import {message} from 'antd'
import {useCallback, useMemo} from 'react'
import {useLazyCreateLocation, useLazyDeleteLocation, useLazyUpdateLocation} from 'shared/libs/query'
import {EditableListWidget} from 'widgets/editableListWidget/EditableListWidget'
import {EditableItem} from 'widgets/editableListWidget/EditableRow'
import {Preloader} from '../preloader/Preloader'
import {useInitializeLocations} from './libs/useInitializeLocations'

const LocationsList = () => {
	const [createLocation] = useLazyCreateLocation()
	const [updateLocation] = useLazyUpdateLocation()
	const [deleteLocation] = useLazyDeleteLocation()
	const {loading, locations, refetch} = useInitializeLocations()


	const handleResponse = useCallback(async (promise: Promise<any>, successMessage: string) => {
		const data = await promise
		if (data.isError) {
			message.error('Что-то пошло не так. Попробуйте ещё раз.')
			return false
		}
		message.success(successMessage)
		refetch()
		return true
	}, [refetch])

	const handleSave = async (newName: string, id?: string) => {
		if (id) {
			await handleResponse(updateLocation({locationId: id, name: newName}), 'Локация успешно обновлёна.')
		}
		else {
			await handleResponse(createLocation({name: newName}), 'Локация успешно добавлена.')
		}
	}

	const handleDelete = async (id: string) => {
		await handleResponse(deleteLocation({locationId: id}), 'Локация успешно удалёна.')
	}

	const items = useMemo(
		(): EditableItem[] =>
			locations.map(location => ({id: location.locationId, name: location.name})),
		[locations],
	)

	if (loading) {
		return <Preloader/>
	}

	return (
		<div>
			<EditableListWidget
				title="Локации"
				data={items}
				onSave={handleSave}
				onDelete={handleDelete}
			/>
		</div>
	)
}

export {
	LocationsList,
}
