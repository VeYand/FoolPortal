import {message} from 'antd'
import {useCallback, useEffect, useMemo} from 'react'
import {useLazyCreateLocation, useLazyDeleteLocation, useLazyUpdateLocation} from 'shared/libs/query'
import {useAppSelector} from 'shared/redux'
import {EditableListWidget} from 'widgets/editableListWidget/EditableListWidget'
import {EditableItem} from 'widgets/editableListWidget/EditableRow'
import {useInitializeLocations} from './libs/useInitializeLocations'

const LocationsList = () => {
	const [createLocation] = useLazyCreateLocation()
	const [updateLocation] = useLazyUpdateLocation()
	const [deleteLocation] = useLazyDeleteLocation()
	const {initialize} = useInitializeLocations()
	const {locations} = useAppSelector(state => state.locationEntity)

	const items = useMemo(
		(): EditableItem[] =>
			locations.map(location => ({id: location.locationId, name: location.name})),
		[locations],
	)

	useEffect(initialize, [initialize])

	const refreshLocations = useCallback(async (action: () => Promise<any>, successMessage: string) => {
		try {
			const data = await action()
			if (data.isSuccess) {
				initialize()
				message.success(successMessage)
			}
			else {
				throw new Error(data.error.data)
			}
		}
		catch (error) {
			message.error('Что-то пошло не так. Попробуйте ещё раз.')
		}
	}, [initialize])

	const handleSave = (newName: string, id?: string) => {
		if (id) {
			refreshLocations(
				() => updateLocation({locationId: id, name: newName}),
				'Локация успешно обновлена',
			)
		}
		else {
			refreshLocations(
				() => createLocation({name: newName}),
				'Локация успешно добавлена',
			)
		}
	}

	const handleDelete = (id: string) => {
		refreshLocations(
			() => deleteLocation({locationId: id}),
			'Локация успешно удалёна',
		)
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
