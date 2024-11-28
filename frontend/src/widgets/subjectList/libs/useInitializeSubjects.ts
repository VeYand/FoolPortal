import {subjectEntitySlice} from 'entities/subject'
import {useCallback} from 'react'
import {useLazyListSubjects} from 'shared/libs/query'
import {remapApiSubjectsToSubjectsList} from 'shared/libs/remmapers/remapApiSubjectsToSubjectsList'
import {useAppDispatch, useAppSelector} from 'shared/redux'

type UseInitializeSubjects = {
	initialize: () => void,
	isLoading: boolean,
}

const useInitializeSubjects = (): UseInitializeSubjects => {
	const dispatch = useAppDispatch()
	const loadingState = useAppSelector(state => state.subjectEntity.loading)
	const [listSubjects, {isLoading, isFetching}] = useLazyListSubjects()

	const initialize = useCallback(async () => {
		dispatch(subjectEntitySlice.actions.setLoading(true))

		const response = await listSubjects({})

		if (response.isError) {
			dispatch(subjectEntitySlice.actions.setLoading(false))
			return
		}

		if (response.data) {
			const subjects = remapApiSubjectsToSubjectsList(response.data.subjects)
			dispatch(subjectEntitySlice.actions.setSubjects(subjects))
		}
	}, [dispatch, listSubjects])

	return {isLoading: isLoading || isFetching || loadingState, initialize}
}

export {
	useInitializeSubjects,
}