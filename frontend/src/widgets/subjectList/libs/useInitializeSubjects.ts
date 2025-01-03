import {message} from 'antd'
import {useCallback, useEffect, useState} from 'react'
import {useLazyListSubjects} from 'shared/libs/query'
import {remapApiSubjectsToSubjectsList} from 'shared/libs/remmapers/remapApiSubjectsToSubjectsList'
import {SubjectData} from 'shared/types'

type UseInitializeSubjects = {
	loading: boolean,
	subjects: SubjectData[],
	refetch: () => void,
}

const useInitializeSubjects = (): UseInitializeSubjects => {
	const [loading, setLoading] = useState(true)
	const [subjects, setSubjects] = useState<SubjectData[]>([])
	const [listSubjects] = useLazyListSubjects()

	const fetch = useCallback(async () => {
		setLoading(true)
		const response = await listSubjects({})

		if (!response.data || response.isError) {
			message.error('Не удалось получить список предметов. Повторите попытку позже.')
			return
		}

		setSubjects(remapApiSubjectsToSubjectsList(response.data.subjects))
		setLoading(false)
	}, [listSubjects])

	useEffect(() => {
		setLoading(true)
		fetch()
	}, [fetch])

	return {loading: loading, subjects, refetch: fetch}
}

export {
	useInitializeSubjects,
}